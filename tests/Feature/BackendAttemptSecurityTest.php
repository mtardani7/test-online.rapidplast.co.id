<?php

namespace Tests\Feature;

use App\Models\Attempt;
use App\Models\AttemptTypeTest;
use App\Models\QuestionChoices;
use App\Models\Questions;
use App\Models\SubTests;
use App\Models\Tests as TestModel;
use App\Models\TypeTests;
use App\Models\User;
use App\Services\AttemptService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BackendAttemptSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        foreach ([
            'attempt_answers', 'attempt_sub_tests', 'attempt_type_tests', 'attempts',
            'question_choices', 'questions', 'sub_tests', 'type_tests', 'tests',
            'model_has_permissions', 'model_has_roles', 'permissions', 'roles', 'users',
            'scoring_component', 'scoring_rules', 'iq_ranges',
        ] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();

        $this->createTables();
    }

    public function test_question_update_and_delete_are_scoped_to_the_route_subtest(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$testA, $typeA, $subA] = $this->makeHierarchy('a');
        [, , $subB] = $this->makeHierarchy('b');
        $questionB = $this->makeQuestion($subB, 'Question B');

        $this->actingAs($admin);
        $route = [$testA->slug, $typeA->slug, $subA->slug, $questionB->id];
        $payload = [
            'question_text' => 'Changed text',
            'options' => ['A' => 'First', 'B' => 'Second'],
            'correct' => 'A',
        ];

        $this->put(route('question.update', $route), $payload)->assertNotFound();
        $this->delete(route('question.destroy', $route))->assertNotFound();

        $this->assertSame('Question B', $questionB->fresh()->question_text);
        $this->assertDatabaseHas('questions', ['id' => $questionB->id, 'sub_tests_id' => $subB->id]);

        $questionA = $this->makeQuestion($subA, 'Question A');
        $this->put(route('question.update', [$testA->slug, $typeA->slug, $subA->slug, $questionA->id]), $payload)
            ->assertRedirect();
        $this->assertSame('Changed text', $questionA->fresh()->question_text);
    }

    public function test_admin_cannot_delete_super_admin_and_super_admin_can_delete_admin(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$superAdmin] = $this->makeUserWithRole('super_admin');
        [$otherAdmin] = $this->makeUserWithRole('admin');

        $this->actingAs($admin)
            ->delete(route('dashboard.users.destroy', $superAdmin->id))
            ->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);

        $this->actingAs($superAdmin)
            ->delete(route('dashboard.users.destroy', $otherAdmin->id))
            ->assertRedirect()
            ->assertSessionHas('success', 'User deleted');
        $this->assertDatabaseMissing('users', ['id' => $otherAdmin->id]);
    }

    public function test_regular_user_cannot_access_user_management_endpoint(): void
    {
        [$user] = $this->makeUserWithRole(null);

        $this->actingAs($user)->get(route('dashboard.users'))->assertForbidden();
    }

    public function test_submit_all_uses_only_server_scoped_questions_and_server_scoring(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('submit');
        [$question, $correctOption] = $this->makeQuestionWithChoices($sub, 'A question');

        $this->actingAs($user)
            ->post(route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]), [
                'answers' => [$question->id => $correctOption->id],
                'is_correct' => false,
                'score' => 999999,
                'correct_option_id' => 0,
                'total_correct' => 999999,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('attempt_answers', [
            'question_id' => $question->id,
            'answer' => (string) $correctOption->id,
            'is_correct' => true,
        ]);
        $attempt = Attempt::where('user_id', $user->id)->where('test_id', $test->id)->firstOrFail();
        $this->assertSame(1, (int) $attempt->total_correct);
        $this->assertNotSame(999999, (int) $attempt->score);
    }

    public function test_submit_all_rejects_question_from_another_subtest(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$testA, $typeA, $subA] = $this->makeHierarchy('submit-a');
        [, , $subB] = $this->makeHierarchy('submit-b');
        [$questionB, $optionB] = $this->makeQuestionWithChoices($subB, 'Question B');

        $this->actingAs($user)
            ->postJson(route('user.questions.submit-all', [$testA->slug, $typeA->slug, $subA->slug]), [
                'answers' => [$questionB->id => $optionB->id],
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount('attempt_answers', 0);
    }

    public function test_submit_all_rejects_option_owned_by_another_question_and_unknown_option(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('options');
        [$questionA] = $this->makeQuestionWithChoices($sub, 'Question A');
        [$questionB, , $optionB] = $this->makeQuestionWithChoices($sub, 'Question B');

        $url = route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]);
        $this->actingAs($user)->postJson($url, ['answers' => [$questionA->id => $optionB->id]])->assertUnprocessable();
        $this->postJson($url, ['answers' => [$questionA->id => 999999]])->assertUnprocessable();
        $this->assertDatabaseCount('attempt_answers', 0);
    }

    public function test_submit_all_accepts_empty_answers_and_rejects_malformed_or_oversized_payloads(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('empty');
        [$question] = $this->makeQuestionWithChoices($sub, 'Question');
        $url = route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]);

        $this->actingAs($user)->post($url, [])->assertRedirect();
        $this->assertDatabaseHas('attempt_sub_tests', ['sub_test_id' => $sub->id, 'is_finished' => true]);

        [$test2, $type2, $sub2] = $this->makeHierarchy('malformed');
        [$question2, $option2] = $this->makeQuestionWithChoices($sub2, 'Question');
        $url2 = route('user.questions.submit-all', [$test2->slug, $type2->slug, $sub2->slug]);

        $this->postJson($url2, ['answers' => ['not-a-question-id' => $option2->id]])->assertUnprocessable();
        $this->postJson($url2, ['answers' => [
            $question2->id => $option2->id,
            $question2->id + 5000 => $option2->id,
        ]])->assertUnprocessable();
        $this->postJson($url2, ['answers' => [
            ['question_id' => $question2->id, 'answer' => $option2->id],
            ['question_id' => $question2->id, 'answer' => $option2->id],
        ]])->assertUnprocessable();
    }

    public function test_submit_all_does_not_change_a_finished_subtest(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('finished');
        [$question, $optionA, $optionB] = $this->makeQuestionWithChoices($sub, 'Question');
        $url = route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]);

        $this->actingAs($user)->post($url, ['answers' => [$question->id => $optionA->id]])->assertRedirect();
        $this->post($url, ['answers' => [$question->id => $optionB->id]])->assertRedirect();

        $this->assertDatabaseHas('attempt_answers', [
            'question_id' => $question->id,
            'answer' => (string) $optionA->id,
            'is_correct' => true,
        ]);
    }

    public function test_single_submit_validates_question_and_option_and_persists_server_correctness(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('single');
        [$question, $correctOption] = $this->makeQuestionWithChoices($sub, 'Question A');
        [, , $foreignOption] = $this->makeQuestionWithChoices($sub, 'Question B');
        [, , $otherSub] = $this->makeHierarchy('single-other');
        [$otherQuestion, $otherOption] = $this->makeQuestionWithChoices($otherSub, 'Question B');
        $url = route('user.questions.submit-single', [$test->slug, $type->slug, $sub->slug]);

        $this->actingAs($user)->post($url, [
            'question_id' => $question->id,
            'answer' => $correctOption->id,
            'is_correct' => false,
        ])->assertRedirect();

        $this->assertDatabaseHas('attempt_answers', [
            'question_id' => $question->id,
            'answer' => (string) $correctOption->id,
            'is_correct' => true,
        ]);

        $this->postJson($url, ['question_id' => $otherQuestion->id, 'answer' => $otherOption->id])->assertUnprocessable();
        $this->postJson($url, ['question_id' => $question->id, 'answer' => $foreignOption->id])->assertUnprocessable();
        $this->postJson($url, ['question_id' => $question->id, 'answer' => 999999])->assertUnprocessable();
    }

    public function test_attempt_service_reuses_attempt_and_child_attempts(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('attempt-service');
        $this->actingAs($user);
        $service = app(AttemptService::class);

        $first = $service->getOrCreateAttempt($test);
        $second = $service->getOrCreateAttempt($test);
        $typeAttemptA = $service->getOrCreateTypeAttempt($first, $type);
        $typeAttemptB = $service->getOrCreateTypeAttempt($second, $type);
        $subAttemptA = $service->getOrCreateSubAttempt($typeAttemptA, $sub);
        $subAttemptB = $service->getOrCreateSubAttempt($typeAttemptB, $sub);

        $this->assertSame($first->id, $second->id);
        $this->assertSame($typeAttemptA->id, $typeAttemptB->id);
        $this->assertSame($subAttemptA->id, $subAttemptB->id);
        $this->assertDatabaseCount('attempts', 1);
        $this->assertDatabaseCount('attempt_type_tests', 1);
        $this->assertDatabaseCount('attempt_sub_tests', 1);
    }

    private function createTables(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique();
            $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('guard_name')->default('web'); $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id'); $table->string('model_type'); $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('guard_name')->default('web'); $table->timestamps();
        });
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id'); $table->string('model_type'); $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
        Schema::create('tests', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('type')->default('OTHER');
            $table->boolean('is_active')->default(true); $table->boolean('is_shuffle')->default(false); $table->timestamps();
        });
        Schema::create('type_tests', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('tests_id'); $table->string('name'); $table->string('slug')->unique(); $table->timestamps();
        });
        Schema::create('sub_tests', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('type_tests_id'); $table->string('name'); $table->string('slug')->unique();
            $table->integer('duration')->nullable(); $table->timestamps();
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('sub_tests_id'); $table->text('question_text')->nullable();
            $table->string('image')->nullable(); $table->text('question_optional')->nullable(); $table->unsignedBigInteger('correct_option_id')->nullable(); $table->timestamps();
        });
        Schema::create('question_choices', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('questions_id'); $table->string('option_label', 5); $table->string('option_text'); $table->timestamps();
        });
        Schema::create('attempts', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('user_id'); $table->unsignedBigInteger('test_id');
            $table->integer('total_correct')->default(0); $table->integer('total_question')->default(0); $table->double('score')->default(0);
            $table->integer('iq_score')->nullable(); $table->string('iq_category')->nullable();
            $table->timestamp('started_at')->nullable(); $table->timestamp('finished_at')->nullable(); $table->timestamps();
        });
        Schema::create('attempt_type_tests', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('attempt_id'); $table->unsignedBigInteger('type_test_id');
            $table->integer('correct')->default(0); $table->integer('total')->default(0); $table->double('score')->default(0); $table->timestamps();
            $table->unique(['attempt_id', 'type_test_id']);
        });
        Schema::create('attempt_sub_tests', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('attempt_type_test_id'); $table->unsignedBigInteger('sub_test_id');
            $table->integer('score')->default(0); $table->integer('correct_count')->default(0); $table->integer('wrong_count')->default(0);
            $table->boolean('is_finished')->default(false); $table->timestamps(); $table->unique(['attempt_type_test_id', 'sub_test_id']);
        });
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('attempt_id'); $table->unsignedBigInteger('question_id');
            $table->string('answer')->nullable(); $table->boolean('is_correct')->default(false); $table->timestamps();
            $table->unique(['attempt_id', 'question_id']);
        });
        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('test_id'); $table->text('formula')->nullable(); $table->text('description')->nullable(); $table->timestamps();
        });
        Schema::create('scoring_component', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('scoring_rule_id'); $table->string('key'); $table->string('description')->nullable(); $table->timestamps();
        });
        Schema::create('iq_ranges', function (Blueprint $table) {
            $table->id(); $table->string('test_type'); $table->integer('raw_min'); $table->integer('raw_max');
            $table->integer('iq_score'); $table->string('category'); $table->timestamps();
        });
    }

    private function makeUserWithRole(?string $roleName): array
    {
        $id = DB::table('users')->insertGetId([
            'name' => $roleName ?? 'Student', 'email' => uniqid('user-', true) . '@example.test',
            'password' => 'hashed', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        $user = User::findOrFail($id);

        if ($roleName) {
            $roleId = DB::table('roles')->where('name', $roleName)->where('guard_name', 'web')->value('id');
            $roleId ??= DB::table('roles')->insertGetId(['name' => $roleName, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
            DB::table('model_has_roles')->insert(['role_id' => $roleId, 'model_type' => User::class, 'model_id' => $id]);
        }

        return [$user];
    }

    private function makeHierarchy(string $suffix): array
    {
        $testId = DB::table('tests')->insertGetId([
            'name' => 'Test ' . $suffix, 'slug' => 'test-' . $suffix, 'type' => 'OTHER',
            'is_active' => true, 'is_shuffle' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $typeId = DB::table('type_tests')->insertGetId([
            'tests_id' => $testId, 'name' => 'Type ' . $suffix, 'slug' => 'type-' . $suffix,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $subId = DB::table('sub_tests')->insertGetId([
            'type_tests_id' => $typeId, 'name' => 'Sub ' . $suffix, 'slug' => 'sub-' . $suffix,
            'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return [TestModel::findOrFail($testId), TypeTests::findOrFail($typeId), SubTests::findOrFail($subId)];
    }

    private function makeQuestion(SubTests $subTest, string $text): Questions
    {
        return Questions::create(['sub_tests_id' => $subTest->id, 'question_text' => $text]);
    }

    private function makeQuestionWithChoices(SubTests $subTest, string $text): array
    {
        $question = $this->makeQuestion($subTest, $text);
        $correct = QuestionChoices::create(['questions_id' => $question->id, 'option_label' => 'A', 'option_text' => 'Correct']);
        $other = QuestionChoices::create(['questions_id' => $question->id, 'option_label' => 'B', 'option_text' => 'Other']);
        $question->update(['correct_option_id' => $correct->id]);

        return [$question, $correct, $other];
    }
}
