<?php

namespace Tests\Feature;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\AttemptSubTest;
use App\Models\AttemptTypeTest;
use App\Models\QuestionChoices;
use App\Models\Questions;
use App\Models\SubTests;
use App\Models\Tests as TestModel;
use App\Models\TypeTests;
use App\Models\User;
use App\Services\AttemptService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BackendAttemptSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        cache()->flush();

        Schema::disableForeignKeyConstraints();
        foreach ([
            'attempt_answers', 'attempt_sub_tests', 'attempt_type_tests', 'attempts',
            'question_choices', 'questions', 'sub_tests', 'type_tests', 'tests',
            'detail_users',
            'model_has_permissions', 'model_has_roles', 'permissions', 'roles', 'users',
            'scoring_component', 'scoring_rules', 'iq_ranges', 'subtest_instruction_ranges',
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

    public function test_user_listing_search_and_filters_run_against_detail_fields(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        $alice = $this->makeListedUser('Alice Example', 'zulu@example.test', [
            'name' => 'Alice Example', 'school' => 'Zeta School', 'gender' => 'female',
            'birth_date' => now()->subYears(20)->toDateString(), 'gpa' => '3.50',
        ]);
        $bob = $this->makeListedUser('Bob Example', 'alpha@example.test', [
            'name' => 'Bob Example', 'school' => 'Alpha School', 'gender' => 'male',
            'birth_date' => now()->subYears(30)->toDateString(), 'gpa' => '2.50',
        ]);
        $carol = $this->makeListedUser('Carol Example', 'charlie@example.test', [
            'name' => 'Carol Example', 'school' => 'Beta School', 'gender' => 'female',
            'birth_date' => now()->subYears(20)->toDateString(), 'gpa' => '4.00',
        ]);
        $this->makeUserWithRole('super_admin');

        $this->actingAs($admin);
        $this->get(route('dashboard.users', ['sort_by' => ['users.email'], 'sort_order' => ['asc']]))
            ->assertOk()->assertViewHas('users', function ($paginator) use ($alice, $bob, $carol) {
                return $paginator->total() === 3
                    && $paginator->getCollection()->pluck('email')->sort()->values()->all()
                        === collect([$alice, $bob, $carol])->pluck('email')->sort()->values()->all();
            });
        foreach ([
            ['search' => 'alice example', 'expected' => [$alice]],
            ['search' => 'ALPHA@EXAMPLE.TEST', 'expected' => [$bob]],
            ['search' => 'beta school', 'expected' => [$carol]],
            ['gender' => 'female', 'expected' => [$alice, $carol]],
            ['min_age' => 25, 'expected' => [$bob]],
            ['max_age' => 20, 'expected' => [$alice, $carol]],
            ['min_gpa' => '3.50', 'expected' => [$alice, $carol]],
            ['gender' => 'female', 'min_age' => 20, 'max_age' => 20, 'min_gpa' => '3.50', 'expected' => [$alice, $carol]],
        ] as $case) {
            $expected = collect($case['expected'])->pluck('email')->sort()->values()->all();
            unset($case['expected']);
            $this->get(route('dashboard.users', $case))
                ->assertOk()
                ->assertViewHas('users', function ($paginator) use ($expected) {
                    return $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                        && $paginator->getCollection()->pluck('email')->sort()->values()->all() === $expected;
                })
                ->assertViewHas('totalUser', count($expected));
        }
    }

    public function test_user_listing_supports_database_sorting_for_each_allowed_column(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        $users = [
            'alice' => $this->makeListedUser('Alice', 'zulu@example.test', ['name' => 'Alice', 'school' => 'Zeta', 'gender' => 'female', 'birth_date' => '2006-01-01', 'gpa' => '3.50'], '2025-01-01 00:00:00'),
            'bob' => $this->makeListedUser('Bob', 'alpha@example.test', ['name' => 'Bob', 'school' => 'Alpha', 'gender' => 'male', 'birth_date' => '1995-01-01', 'gpa' => '2.50'], '2025-01-03 00:00:00'),
            'carol' => $this->makeListedUser('Carol', 'charlie@example.test', ['name' => 'Carol', 'school' => 'Beta', 'gender' => 'female', 'birth_date' => '2006-06-01', 'gpa' => '4.00'], '2025-01-02 00:00:00'),
            'unknown' => $this->makeListedUser('Unknown', 'delta@example.test', [], '2025-01-04 00:00:00'),
        ];
        $this->actingAs($admin);

        $cases = [
            ['name', 'asc', 'zulu@example.test', 'delta@example.test'], ['name', 'desc', 'delta@example.test', 'zulu@example.test'],
            ['email', 'asc', 'alpha@example.test', 'zulu@example.test'], ['email', 'desc', 'zulu@example.test', 'alpha@example.test'],
            ['gender', 'asc', 'delta@example.test', 'alpha@example.test'], ['gender', 'desc', 'alpha@example.test', 'delta@example.test'],
            ['age', 'asc', 'delta@example.test', 'alpha@example.test'], ['age', 'desc', 'alpha@example.test', 'delta@example.test'],
            ['school', 'asc', 'delta@example.test', 'zulu@example.test'], ['school', 'desc', 'zulu@example.test', 'delta@example.test'],
            ['gpa', 'asc', 'delta@example.test', 'charlie@example.test'], ['gpa', 'desc', 'charlie@example.test', 'delta@example.test'],
            ['created_at', 'asc', 'zulu@example.test', 'delta@example.test'], ['unknown', 'desc', 'delta@example.test', 'zulu@example.test'],
        ];
        foreach ($cases as [$sort, $direction, $first, $last]) {
            $response = $this->get(route('dashboard.users', ['sort_by' => $sort, 'sort_order' => $direction, 'per_page' => 20]));
            $response->assertOk();
            $this->assertSame(
                [$first, $last],
                [$response->viewData('users')->getCollection()->first()->email, $response->viewData('users')->getCollection()->last()->email],
                "Unexpected endpoint ordering for {$sort} {$direction}."
            );
        }
    }

    public function test_user_listing_paginates_in_database_and_preserves_query_parameters(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        for ($index = 1; $index <= 15; $index++) {
            $this->makeListedUser('Candidate '.$index, sprintf('candidate-%02d@example.test', $index), [
                'name' => 'Candidate '.$index, 'gender' => 'female', 'school' => 'School', 'gpa' => '3.00',
            ], sprintf('2025-01-%02d 00:00:00', $index));
        }
        $this->actingAs($admin);

        $default = $this->get(route('dashboard.users'));
        $default->assertOk()->assertViewHas('users', function ($paginator) {
            return $paginator->perPage() === 10 && $paginator->count() === 10 && $paginator->total() === 15;
        });
        $this->get(route('dashboard.users', ['per_page' => 0]))->assertViewHas('users', fn ($paginator) => $paginator->perPage() === 10);

        $queryCount = 0;
        DB::listen(function ($query) use (&$queryCount) {
            if (str_contains(strtolower($query->sql), 'from "users"') && str_contains(strtolower($query->sql), 'limit')) {
                $queryCount++;
            }
        });
        $response = $this->get(route('dashboard.users', ['gender' => 'female', 'per_page' => 5, 'page' => 2]));
        $response->assertOk()->assertViewHas('users', function ($paginator) {
            return $paginator->perPage() === 5
                && $paginator->currentPage() === 2
                && $paginator->total() === 15
                && $paginator->getCollection()->pluck('email')->all() === [
                    'candidate-10@example.test', 'candidate-09@example.test', 'candidate-08@example.test',
                    'candidate-07@example.test', 'candidate-06@example.test',
                ]
                && str_contains($paginator->url(3), 'gender=female');
        })->assertViewHas('totalUser', 15);
        $this->assertGreaterThanOrEqual(1, $queryCount, 'The paginated data query should have a SQL limit.');
    }

    private function makeListedUser(string $label, string $email, array $detail, ?string $createdAt = null): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => $label, 'email' => $email, 'password' => 'hashed', 'email_verified_at' => now(),
            'created_at' => $createdAt ?? now(), 'updated_at' => now(),
        ]);
        DB::table('detail_users')->insert(array_merge([
            'user_id' => $id, 'created_at' => now(), 'updated_at' => now(),
        ], $detail));

        return User::findOrFail($id);
    }

    public function test_submit_all_uses_only_server_scoped_questions_and_server_scoring(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('submit');
        [$question, $correctOption] = $this->makeQuestionWithChoices($sub, 'A question');
        [$wrongQuestion, , $wrongOption] = $this->makeQuestionWithChoices($sub, 'Another question');
        DB::table('tests')->where('id', $test->id)->update(['type' => 'TPA']);
        DB::table('iq_ranges')->insert([
            'test_type' => 'TPA', 'raw_min' => 1, 'raw_max' => 1, 'iq_score' => 115,
            'category' => 'Above Average', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $queries = [];
        $captureQueries = true;
        DB::listen(function ($query) use (&$queries, &$captureQueries) {
            if ($captureQueries) {
                $queries[] = strtolower($query->sql);
            }
        });

        $this->actingAs($user)
            ->post(route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]), [
                'answers' => [$question->id => $correctOption->id, $wrongQuestion->id => $wrongOption->id],
                'is_correct' => false,
                'score' => 999999,
                'correct_option_id' => 0,
                'total_correct' => 999999,
            ])
            ->assertRedirect();
        $captureQueries = false;

        $this->assertDatabaseHas('attempt_answers', [
            'question_id' => $question->id,
            'answer' => (string) $correctOption->id,
            'is_correct' => true,
        ]);
        $this->assertDatabaseHas('attempt_answers', [
            'question_id' => $wrongQuestion->id,
            'answer' => (string) $wrongOption->id,
            'is_correct' => false,
        ]);
        $attempt = Attempt::where('user_id', $user->id)->where('test_id', $test->id)->firstOrFail();
        $subAttempt = AttemptSubTest::where('sub_test_id', $sub->id)->firstOrFail();
        $typeAttempt = AttemptTypeTest::where('type_test_id', $type->id)->firstOrFail();

        $this->assertSame(1, (int) $subAttempt->correct_count);
        $this->assertSame(1, (int) $subAttempt->wrong_count);
        $this->assertTrue((bool) $subAttempt->is_finished);
        $this->assertSame(1.0, (float) $subAttempt->score);
        $this->assertSame(1, (int) $typeAttempt->correct);
        $this->assertSame(2, (int) $typeAttempt->total);
        $this->assertSame(1.0, (float) $typeAttempt->score);
        $this->assertSame(1, (int) $attempt->total_correct);
        $this->assertSame(2, (int) $attempt->total_question);
        $this->assertSame(1.0, (float) $attempt->score);
        $this->assertSame(115, (int) $attempt->iq_score);
        $this->assertSame('Above Average', $attempt->iq_category);

        $answerReads = array_values(array_filter($queries, fn ($sql) => str_contains($sql, 'from "attempt_answers"')));
        $this->assertCount(1, $answerReads);
        $this->assertStringContainsString('sum(case when is_correct = 1', $answerReads[0]);
        $this->assertStringContainsString('sum(case when is_correct = 0', $answerReads[0]);
        foreach ($queries as $sql) {
            if (str_contains($sql, 'attempt_sub_tests') || str_contains($sql, 'attempt_type_tests')) {
                $this->assertStringNotContainsString('sum(', $sql);
            }
        }
    }

    public function test_submit_all_preserves_omitted_answers_and_null_clears_only_submitted_question(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $type, $sub] = $this->makeHierarchy('partial-submit');
        [$questionOne, $optionOne] = $this->makeQuestionWithChoices($sub, 'Question one');
        [$questionTwo, $optionTwo, $optionTwoC] = $this->makeQuestionWithChoices($sub, 'Question two');
        $url = route('user.questions.submit-all', [$test->slug, $type->slug, $sub->slug]);

        $this->actingAs($user)->post(route('user.questions.submit-single', [$test->slug, $type->slug, $sub->slug]), [
            'question_id' => $questionOne->id, 'answer' => $optionOne->id,
        ])->assertRedirect();
        $this->post(route('user.questions.submit-single', [$test->slug, $type->slug, $sub->slug]), [
            'question_id' => $questionTwo->id, 'answer' => $optionTwo->id,
        ])->assertRedirect();

        $this->post($url, ['answers' => [$questionTwo->id => $optionTwoC->id]])->assertRedirect();
        $this->assertDatabaseHas('attempt_answers', ['question_id' => $questionOne->id, 'answer' => (string) $optionOne->id]);
        $this->assertDatabaseHas('attempt_answers', ['question_id' => $questionTwo->id, 'answer' => (string) $optionTwoC->id]);
        $this->assertDatabaseHas('attempt_sub_tests', [
            'sub_test_id' => $sub->id, 'correct_count' => 1, 'wrong_count' => 1,
        ]);

        [$test2, $type2, $sub2] = $this->makeHierarchy('null-submit');
        [$questionNull, $optionNull] = $this->makeQuestionWithChoices($sub2, 'Question null');
        $url2 = route('user.questions.submit-all', [$test2->slug, $type2->slug, $sub2->slug]);
        $this->post(route('user.questions.submit-single', [$test2->slug, $type2->slug, $sub2->slug]), [
            'question_id' => $questionNull->id, 'answer' => $optionNull->id,
        ])->assertRedirect();
        $this->post($url2, ['answers' => [$questionNull->id => null]])->assertRedirect();
        $this->assertDatabaseHas('attempt_answers', ['question_id' => $questionNull->id, 'answer' => null, 'is_correct' => false]);
    }

    public function test_questions_no_range_reuses_loaded_answers_and_loads_only_current_choices(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $typeTest, $subTest] = $this->makeHierarchy('questions-no-range-read-path');
        $questions = [
            $this->makeQuestion($subTest, 'Question one'),
            $this->makeQuestion($subTest, 'Question two'),
            $this->makeQuestion($subTest, 'Question three'),
        ];
        $choices = [];
        foreach ($questions as $index => $question) {
            $choices[$index] = QuestionChoices::create([
                'questions_id' => $question->id, 'option_label' => 'A', 'option_text' => 'Choice '.$index,
            ]);
        }
        $attemptService = app(AttemptService::class);
        $attempt = $attemptService->getOrCreateAttempt($test, $user->id);
        $typeAttempt = $attemptService->getOrCreateTypeAttempt($attempt, $typeTest);
        $attemptService->getOrCreateSubAttempt($typeAttempt, $subTest);
        AttemptAnswer::create(['attempt_id' => $attempt->id, 'question_id' => $questions[0]->id, 'answer' => null, 'is_correct' => false]);
        AttemptAnswer::create(['attempt_id' => $attempt->id, 'question_id' => $questions[1]->id, 'answer' => $choices[1]->id, 'is_correct' => true]);
        AttemptAnswer::create(['attempt_id' => $attempt->id, 'question_id' => $questions[2]->id, 'answer' => $choices[2]->id, 'is_correct' => false]);

        $queries = [];
        $captureQueries = true;
        DB::listen(function ($query) use (&$queries, &$captureQueries) {
            if ($captureQueries) {
                $queries[] = ['sql' => strtolower($query->sql), 'bindings' => $query->bindings];
            }
        });
        $this->actingAs($user);
        $view = app(\App\Http\Controllers\UserTestController::class)->questions(
            $test,
            $typeTest,
            $subTest,
            Request::create('/?question=2', 'GET')
        );
        $captureQueries = false;

        $data = $view->getData();
        $this->assertSame($questions[1]->id, $data['currentQuestion']->id);
        $this->assertTrue($data['currentQuestion']->relationLoaded('choices'));
        $this->assertSame([$choices[1]->id], $data['currentQuestion']->choices->pluck('id')->all());
        $this->assertSame($choices[1]->id, (int) $data['previousAnswer']->answer);
        $this->assertSame(2, $data['answeredQuestions']);

        $questionReads = array_values(array_filter($queries, fn ($query) => str_contains($query['sql'], 'from "questions"')));
        $this->assertCount(1, $questionReads);
        $this->assertStringContainsString('select "id", "question_text", "question_optional", "image" from "questions"', $questionReads[0]['sql']);
        $this->assertStringNotContainsString('select * from "questions"', $questionReads[0]['sql']);
        $choiceReads = array_values(array_filter($queries, fn ($query) => str_contains($query['sql'], 'from "question_choices"')));
        $this->assertCount(1, $choiceReads);
        $this->assertStringContainsString('questions_id" in ('.$questions[1]->id.')', $choiceReads[0]['sql']);
        $answerReads = array_values(array_filter($queries, fn ($query) => str_contains($query['sql'], 'from "attempt_answers"')));
        $this->assertCount(1, $answerReads, 'No-range handling should reuse the already-loaded answer collection.');
    }

    public function test_questions_range_path_keeps_current_question_choices_available(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $typeTest, $subTest] = $this->makeHierarchy('questions-range-read-path');
        $firstQuestion = $this->makeQuestion($subTest, 'Question one');
        $currentQuestion = $this->makeQuestion($subTest, 'Question two');
        $choice = QuestionChoices::create([
            'questions_id' => $currentQuestion->id, 'option_label' => 'A', 'option_text' => 'Current choice',
        ]);
        DB::table('subtest_instruction_ranges')->insert([
            'sub_test_id' => $subTest->id, 'start_number' => $firstQuestion->id, 'end_number' => $currentQuestion->id,
            'order_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs($user);

        $view = app(\App\Http\Controllers\UserTestController::class)->questions(
            $test,
            $typeTest,
            $subTest,
            Request::create('/?question=2', 'GET')
        );

        $data = $view->getData();
        $this->assertSame($currentQuestion->id, $data['currentQuestion']->id);
        $this->assertSame([$choice->id], $data['currentQuestion']->choices->pluck('id')->all());
    }

    public function test_question_create_and_edit_routes_use_existing_index_flow_and_validate_hierarchy(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$testA, $typeA, $subA] = $this->makeHierarchy('question-route-a');
        [, , $subB] = $this->makeHierarchy('question-route-b');
        $questionB = $this->makeQuestion($subB, 'Question B');
        $this->actingAs($admin);

        $this->get(route('question.create', [$testA->slug, $typeA->slug, $subA->slug]))
            ->assertRedirect(route('question.index', [$testA->slug, $typeA->slug, $subA->slug]));
        $this->get(route('question.edit', [$testA->slug, $typeA->slug, $subA->slug, $questionB->id]))->assertNotFound();
        $questionA = $this->makeQuestion($subA, 'Question A');
        $this->get(route('question.edit', [$testA->slug, $typeA->slug, $subA->slug, $questionA->id]))
            ->assertRedirect(route('question.index', [$testA->slug, $typeA->slug, $subA->slug]));
    }

    public function test_question_index_sort_filters_pagination_statistics_and_hierarchy(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$test, $typeTest, $subTest] = $this->makeHierarchy('question-index-phase-6g');
        [, , $otherSubTest] = $this->makeHierarchy('question-index-other-6g');

        $questions = [];
        for ($index = 1; $index <= 12; $index++) {
            $text = $index === 1 ? 'Needle searchable question' : sprintf('Question %02d', $index);
            $question = $this->makeQuestion($subTest, $text);
            DB::table('questions')->where('id', $question->id)->update([
                'created_at' => sprintf('2025-01-%02d 00:00:00', $index),
                'image' => $index % 3 === 0 ? 'questions/image-'.$index.'.jpg' : null,
            ]);
            $choiceCount = $index <= 6 ? 2 : 1;
            for ($choiceIndex = 1; $choiceIndex <= $choiceCount; $choiceIndex++) {
                $choiceId = DB::table('question_choices')->insertGetId([
                    'questions_id' => $question->id,
                    'option_label' => chr(64 + $choiceIndex),
                    'option_text' => $index === 2 && $choiceIndex === 1 ? 'Choice Needle' : "Option {$index}-{$choiceIndex}",
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                if ($choiceIndex === 1 && $index % 2 === 0) {
                    DB::table('questions')->where('id', $question->id)->update(['correct_option_id' => $choiceId]);
                }
            }
            $questions[$index] = $question;
        }
        $foreignQuestion = $this->makeQuestion($otherSubTest, 'Foreign question');

        $this->actingAs($admin);
        $url = fn (array $query = []) => route('question.index', [$test->slug, $typeTest->slug, $subTest->slug] + $query);
        $response = $this->get($url());
        $response->assertOk()->assertViewHas('questions', function ($paginator) use ($questions) {
            return $paginator->perPage() === 10
                && $paginator->total() === 12
                && $paginator->first()->id === $questions[12]->id;
        })->assertViewHas('totalQuestions', 12)
            ->assertViewHas('questionsWithImage', 4)
            ->assertViewHas('avgChoices', 1.5);

        foreach ([
            [['sort_by' => 'created_at', 'sort_order' => 'asc'], $questions[1]->id, $questions[10]->id],
            [['sort_by' => 'question_text', 'sort_order' => 'asc'], $questions[1]->id, $questions[10]->id],
            [['sort_by' => 'question_text', 'sort_order' => 'desc'], $questions[12]->id, $questions[3]->id],
            [['sort_by' => 'question_text; DROP TABLE questions', 'sort_order' => 'asc'], $questions[1]->id, $questions[10]->id],
            [['sort_by' => 'question_text', 'sort_order' => 'desc, (SELECT 1)'], $questions[12]->id, $questions[3]->id],
        ] as [$query, $firstId, $lastId]) {
            $sorted = $this->get($url($query))->assertOk()->viewData('questions');
            $this->assertSame($firstId, $sorted->getCollection()->first()->id);
            $this->assertSame($lastId, $sorted->getCollection()->last()->id);
        }

        $this->get($url(['search' => 'Needle']))->assertViewHas('questions', fn ($paginator) => $paginator->total() === 2);
        $this->get($url(['has_image' => 'yes']))->assertViewHas('questions', fn ($paginator) => $paginator->total() === 4);
        $this->get($url(['has_image' => 'no']))->assertViewHas('questions', fn ($paginator) => $paginator->total() === 8);
        $this->get($url(['has_correct' => 'yes']))->assertViewHas('questions', fn ($paginator) => $paginator->total() === 6);
        $this->get($url(['has_correct' => 'no']))->assertViewHas('questions', fn ($paginator) => $paginator->total() === 6);

        $pageTwo = $this->get($url(['search' => 'Question', 'sort_by' => 'question_text', 'sort_order' => 'asc', 'page' => 2]));
        $pageTwo->assertOk()->assertViewHas('questions', function ($paginator) {
            return $paginator->perPage() === 10
                && $paginator->currentPage() === 2
                && $paginator->total() === 12
                && str_contains($paginator->url(1), 'search=Question')
                && str_contains($paginator->url(1), 'sort_by=question_text');
        });

        $this->get(route('question.index', [$test->slug, $typeTest->slug, $otherSubTest->slug]))->assertNotFound();
        $this->assertDatabaseHas('questions', ['id' => $foreignQuestion->id, 'sub_tests_id' => $otherSubTest->id]);
    }

    public function test_home_create_and_edit_routes_reuse_existing_test_management_view(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$test] = $this->makeHierarchy('home-route');
        $this->actingAs($admin);

        $this->get(route('test.create'))->assertRedirect(route('test.index'));
        $this->get(route('test.edit', $test->id))->assertRedirect(route('test.index'));
        $this->get(route('test.edit', 999999))->assertNotFound();
    }

    public function test_shuffled_order_rejects_subtest_from_another_test(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$testA, , $subA] = $this->makeHierarchy('shuffle-a');
        [, , $subB] = $this->makeHierarchy('shuffle-b');
        $this->actingAs($user);
        $controller = app(\App\Http\Controllers\UserTestController::class);

        $allowed = $controller->getShuffledOrder(Request::create('/'), $testA, $subA);
        $this->assertSame(200, $allowed->getStatusCode());
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $controller->getShuffledOrder(Request::create('/'), $testA, $subB);
    }

    public function test_legacy_attempt_routes_are_not_registered(): void
    {
        foreach (['attempt.start', 'attempt.answer', 'attempt.finish', 'attempt.result'] as $routeName) {
            $this->assertFalse(Route::has($routeName), "Legacy route {$routeName} should not be registered.");
        }

        $legacyActions = collect(Route::getRoutes())->pluck('action.uses')->filter(function ($action) {
            return is_string($action) && str_starts_with($action, 'App\\Http\\Controllers\\AttemptController@');
        });
        foreach (['start', 'saveAnswer', 'finish', 'result'] as $method) {
            $this->assertFalse($legacyActions->contains("App\\Http\\Controllers\\AttemptController@{$method}"));
        }
    }

    public function test_complete_range_is_scoped_to_subtest_and_test_hierarchy(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$testA, $typeA, $subA] = $this->makeHierarchy('complete-range-a');
        [$testB, $typeB, $subB] = $this->makeHierarchy('complete-range-b');
        $rangeAId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $subA->id, 'start_number' => 1, 'end_number' => 1,
            'order_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $rangeBId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $subB->id, 'start_number' => 1, 'end_number' => 1,
            'order_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $controller = app(\App\Http\Controllers\UserTestController::class);
        $this->actingAs($user);

        $valid = $controller->completeRange(Request::create('/', 'POST', ['range_id' => $rangeAId]), $testA, $typeA, $subA);
        $this->assertSame(302, $valid->getStatusCode());
        $this->assertDatabaseCount('attempts', 0);

        try {
            $controller->completeRange(Request::create('/', 'POST', ['range_id' => $rangeBId]), $testA, $typeA, $subA);
            $this->fail('A range owned by another subtest must be rejected.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            $this->assertSame(\App\Models\SubtestInstructionRange::class, $exception->getModel());
        }

        try {
            $controller->completeRange(Request::create('/', 'POST', ['range_id' => $rangeAId]), $testB, $typeB, $subA);
            $this->fail('A mismatched test/type/subtest hierarchy must be rejected.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertSame(404, $exception->getStatusCode());
        }
    }

    public function test_complete_range_preserves_next_question_navigation_and_uses_id_only_query(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $typeTest, $subTest] = $this->makeHierarchy('complete-range-navigation');
        $questions = [];
        for ($index = 1; $index <= 4; $index++) {
            $questions[] = $this->makeQuestion($subTest, 'Question '.$index);
        }
        $firstRangeId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $subTest->id, 'start_number' => $questions[0]->id, 'end_number' => $questions[1]->id,
            'order_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $nextRangeId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $subTest->id, 'start_number' => $questions[2]->id, 'end_number' => $questions[3]->id,
            'order_number' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $inactiveRangeId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $subTest->id, 'start_number' => $questions[0]->id, 'end_number' => $questions[0]->id,
            'order_number' => 3, 'is_active' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $controller = app(\App\Http\Controllers\UserTestController::class);
        $this->actingAs($user);

        $queries = [];
        $captureQueries = true;
        DB::listen(function ($query) use (&$queries, &$captureQueries) {
            if ($captureQueries) {
                $queries[] = strtolower($query->sql);
            }
        });
        $next = $controller->completeRange(Request::create('/', 'POST', ['range_id' => $firstRangeId]), $test, $typeTest, $subTest);
        $captureQueries = false;

        $this->assertSame(route('user.questions', [
            'test' => $test->slug, 'typeTest' => $typeTest->slug, 'subTest' => $subTest->slug,
            'question' => 3, 'range_id' => $nextRangeId,
        ]), $next->getTargetUrl());
        $questionReads = array_values(array_filter($queries, fn ($sql) => str_contains($sql, 'from "questions"')));
        $this->assertCount(1, $questionReads);
        $this->assertStringContainsString('select "id" from "questions"', $questionReads[0]);
        foreach ($queries as $sql) {
            $this->assertStringNotContainsString('insert into "attempts"', $sql);
        }
        $this->assertDatabaseCount('attempts', 0);

        $lastRange = $controller->completeRange(Request::create('/', 'POST', ['range_id' => $nextRangeId]), $test, $typeTest, $subTest);
        $this->assertSame(route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]), $lastRange->getTargetUrl());
        $inactiveRange = $controller->completeRange(Request::create('/', 'POST', ['range_id' => $inactiveRangeId]), $test, $typeTest, $subTest);
        $this->assertSame(route('user.sub-tests', ['test' => $test->slug, 'typeTest' => $typeTest->slug]), $inactiveRange->getTargetUrl());

        [$shuffleTest, $shuffleType, $shuffleSub] = $this->makeHierarchy('complete-range-shuffled-navigation');
        DB::table('tests')->where('id', $shuffleTest->id)->update(['is_shuffle' => true]);
        $shuffleTest->refresh();
        $shuffledQuestions = [];
        for ($index = 1; $index <= 4; $index++) {
            $shuffledQuestions[] = $this->makeQuestion($shuffleSub, 'Shuffled question '.$index);
        }
        $shuffleFirstRangeId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $shuffleSub->id, 'start_number' => $shuffledQuestions[0]->id, 'end_number' => $shuffledQuestions[1]->id,
            'order_number' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $shuffleNextRangeId = DB::table('subtest_instruction_ranges')->insertGetId([
            'sub_test_id' => $shuffleSub->id, 'start_number' => $shuffledQuestions[2]->id, 'end_number' => $shuffledQuestions[3]->id,
            'order_number' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $allQuestions = $shuffleSub->questions()->select('id')->orderBy('id')->get();
        $shuffleService = app(\App\Services\ShuffleService::class);
        $expectedQuestionNumber = $shuffleService->createQuestionIdToNumberMap(
            $shuffleService->getUniqueShuffledQuestions($user, $shuffleTest, $shuffleSub, $allQuestions)
        )[$shuffledQuestions[2]->id] ?? 1;

        $shuffleNext = $controller->completeRange(
            Request::create('/', 'POST', ['range_id' => $shuffleFirstRangeId]),
            $shuffleTest,
            $shuffleType,
            $shuffleSub
        );
        $this->assertSame(route('user.questions', [
            'test' => $shuffleTest->slug, 'typeTest' => $shuffleType->slug, 'subTest' => $shuffleSub->slug,
            'question' => $expectedQuestionNumber, 'range_id' => $shuffleNextRangeId,
        ]), $shuffleNext->getTargetUrl());
        $this->assertDatabaseCount('attempts', 0);
    }

    public function test_question_routes_enforce_all_three_levels_of_test_hierarchy(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$testA, $typeA, $subA] = $this->makeHierarchy('hierarchy-route-a');
        [, , $subB] = $this->makeHierarchy('hierarchy-route-b');
        $typeBId = DB::table('type_tests')->insertGetId([
            'tests_id' => $testA->id, 'name' => 'Type B', 'slug' => 'type-b-under-a',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $subUnderTypeBId = DB::table('sub_tests')->insertGetId([
            'type_tests_id' => $typeBId, 'name' => 'Sub under B', 'slug' => 'sub-under-b',
            'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $typeB = TypeTests::findOrFail($typeBId);
        $subUnderTypeB = SubTests::findOrFail($subUnderTypeBId);
        $question = $this->makeQuestion($subA, 'Scoped question');
        $this->actingAs($admin);

        $this->get(route('question.edit', [$testA->slug, $typeA->slug, $subA->slug, $question->id]))->assertRedirect();
        $this->get(route('question.edit', [$testA->slug, $typeA->slug, $subB->slug, $question->id]))->assertNotFound();
        $this->get(route('question.edit', [$testA->slug, $typeB->slug, $subA->slug, $question->id]))->assertNotFound();
        $this->get(route('question.edit', [$testA->slug, $typeB->slug, $subUnderTypeB->slug, $question->id]))->assertNotFound();
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

    public function test_user_test_listing_query_count_is_batched_as_test_count_grows(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$completedTest, $typeTest, $subTest] = $this->makeHierarchy('batch-listing');
        $attempt = Attempt::create([
            'user_id' => $user->id, 'test_id' => $completedTest->id,
            'total_correct' => 0, 'total_question' => 0, 'score' => 0,
        ]);
        $typeAttempt = AttemptTypeTest::create([
            'attempt_id' => $attempt->id, 'type_test_id' => $typeTest->id,
            'correct' => 0, 'total' => 0, 'score' => 0,
        ]);
        \App\Models\AttemptSubTest::create([
            'attempt_type_test_id' => $typeAttempt->id, 'sub_test_id' => $subTest->id,
            'is_finished' => true, 'correct_count' => 0, 'wrong_count' => 0,
        ]);

        $this->actingAs($user);
        $recordQueries = false;
        $queryCount = 0;
        DB::listen(function ($query) use (&$recordQueries, &$queryCount) {
            if ($recordQueries) {
                $queryCount++;
            }
        });

        $recordQueries = true;
        $firstView = app(\App\Http\Controllers\UserTestController::class)->tests();
        $recordQueries = false;
        $oneTestQueryCount = $queryCount;
        $this->assertTrue($firstView->getData()['tests']->first()->all_type_tests_completed);
        $this->assertSame(10, (int) $firstView->getData()['tests']->first()->total_duration);

        foreach (['batch-listing-two', 'batch-listing-three', 'batch-listing-four'] as $suffix) {
            $this->makeHierarchy($suffix);
        }

        $queryCount = 0;
        $recordQueries = true;
        $multipleTestsView = app(\App\Http\Controllers\UserTestController::class)->tests();
        $recordQueries = false;
        $this->assertSame($oneTestQueryCount, $queryCount);
        $this->assertSame(4, $multipleTestsView->getData()['tests']->count());
        $this->assertTrue($multipleTestsView->getData()['tests']->first()->all_type_tests_completed);
        $this->assertFalse($multipleTestsView->getData()['tests']->last()->all_type_tests_completed);
    }

    public function test_type_test_completion_queries_are_batched_as_type_count_grows(): void
    {
        [$user] = $this->makeUserWithRole(null);
        DB::table('detail_users')->insert([
            'user_id' => $user->id, 'no_id' => 'ID-' . $user->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        [$test, $firstType, $firstSubTest] = $this->makeHierarchy('batch-type-listing');
        $attempt = Attempt::create([
            'user_id' => $user->id, 'test_id' => $test->id,
            'total_correct' => 0, 'total_question' => 0, 'score' => 0,
        ]);
        $firstTypeAttempt = AttemptTypeTest::create([
            'attempt_id' => $attempt->id, 'type_test_id' => $firstType->id,
            'correct' => 0, 'total' => 0, 'score' => 0,
        ]);
        \App\Models\AttemptSubTest::create([
            'attempt_type_test_id' => $firstTypeAttempt->id, 'sub_test_id' => $firstSubTest->id,
            'is_finished' => true, 'correct_count' => 0, 'wrong_count' => 0,
        ]);

        $this->actingAs($user);
        $capture = false;
        $subAttemptQueryCount = 0;
        DB::listen(function ($query) use (&$capture, &$subAttemptQueryCount) {
            if ($capture && str_contains(strtolower($query->sql), 'attempt_sub_tests')) {
                $subAttemptQueryCount++;
            }
        });

        $capture = true;
        $smallView = app(\App\Http\Controllers\UserTestController::class)->typeTests($test);
        $capture = false;
        $smallQueryCount = $subAttemptQueryCount;
        $this->assertTrue($smallView->getData()['typeTests']->first()->all_subtests_completed);

        foreach ([2, 3, 4] as $index) {
            $typeId = DB::table('type_tests')->insertGetId([
                'tests_id' => $test->id, 'name' => "Type {$index}", 'slug' => "batch-type-{$index}",
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $subTestId = DB::table('sub_tests')->insertGetId([
                'type_tests_id' => $typeId, 'name' => "Sub {$index}", 'slug' => "batch-sub-{$index}",
                'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $typeAttemptId = DB::table('attempt_type_tests')->insertGetId([
                'attempt_id' => $attempt->id, 'type_test_id' => $typeId,
                'correct' => 0, 'total' => 0, 'score' => 0, 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('attempt_sub_tests')->insert([
                'attempt_type_test_id' => $typeAttemptId, 'sub_test_id' => $subTestId,
                'score' => 0, 'correct_count' => 0, 'wrong_count' => 0,
                'is_finished' => $index !== 2, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $subAttemptQueryCount = 0;
        $capture = true;
        $largeView = app(\App\Http\Controllers\UserTestController::class)->typeTests($test->fresh());
        $capture = false;
        $this->assertSame($smallQueryCount, $subAttemptQueryCount);
        $typeTests = $largeView->getData()['typeTests']->keyBy('id');
        $this->assertTrue($typeTests->get($firstType->id)->all_subtests_completed);
        $this->assertFalse($typeTests->get(2)->all_subtests_completed);
        $this->assertTrue($typeTests->get(3)->all_subtests_completed);
        $this->assertSame(4, $typeTests->count());
    }

    public function test_subtest_progress_and_status_reads_are_batched_as_subtest_count_grows(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $typeTest, $firstSubTest] = $this->makeHierarchy('batch-subtest-listing');
        $attempt = Attempt::create([
            'user_id' => $user->id, 'test_id' => $test->id,
            'total_correct' => 0, 'total_question' => 0, 'score' => 0,
        ]);
        $typeAttempt = AttemptTypeTest::create([
            'attempt_id' => $attempt->id, 'type_test_id' => $typeTest->id,
            'correct' => 0, 'total' => 0, 'score' => 0,
        ]);

        $seedSubtest = function (SubTests $subTest, bool $finished) use ($attempt, $typeAttempt): void {
            DB::table('attempt_sub_tests')->insert([
                'attempt_type_test_id' => $typeAttempt->id, 'sub_test_id' => $subTest->id,
                'score' => 0, 'correct_count' => 5, 'wrong_count' => 0,
                'is_finished' => $finished, 'created_at' => now(), 'updated_at' => now(),
            ]);

            for ($index = 1; $index <= 10; $index++) {
                $questionId = DB::table('questions')->insertGetId([
                    'sub_tests_id' => $subTest->id, 'question_text' => "Question {$index}",
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                if ($index <= 5) {
                    DB::table('attempt_answers')->insert([
                        'attempt_id' => $attempt->id, 'question_id' => $questionId,
                        'answer' => 'A', 'is_correct' => true, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        };
        $seedSubtest($firstSubTest, true);
        $this->actingAs($user);

        $capture = false;
        $readQueryCount = 0;
        DB::listen(function ($query) use (&$capture, &$readQueryCount) {
            if (!$capture) {
                return;
            }

            $sql = strtolower($query->sql);
            $isBatchRead = str_contains($sql, 'from "questions"')
                || str_contains($sql, 'from "attempt_answers"')
                || (str_contains($sql, 'from "attempt_sub_tests"') && !str_contains($sql, '"sub_test_id"'));

            if ($isBatchRead) {
                $readQueryCount++;
            }
        });

        $capture = true;
        $smallView = app(\App\Http\Controllers\UserTestController::class)->subTests($test, $typeTest);
        $capture = false;
        $smallReadQueryCount = $readQueryCount;
        $smallSubTest = $smallView->getData()['subTests']->first();
        $this->assertSame(10, $smallSubTest->total_questions);
        $this->assertSame(5, $smallSubTest->correct_count);
        $this->assertEquals(50, $smallSubTest->progress);
        $this->assertSame(5, $smallSubTest->non_null_answers_count);
        $this->assertSame(5, $smallSubTest->null_answers_count);
        $this->assertFalse($smallSubTest->all_answered);
        $this->assertTrue($smallSubTest->completed);
        $this->assertTrue($smallView->getData()['allSubTestsCompleted']);
        $this->assertEquals(50, $smallView->getData()['completionRate']);
        $this->assertSame(10, (int) $smallView->getData()['totalDuration']);

        foreach ([2, 3, 4] as $index) {
            $subTestId = DB::table('sub_tests')->insertGetId([
                'type_tests_id' => $typeTest->id, 'name' => "Subtest {$index}", 'slug' => "batch-subtest-{$index}",
                'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $seedSubtest(SubTests::findOrFail($subTestId), $index === 3);
        }

        $readQueryCount = 0;
        $capture = true;
        $largeView = app(\App\Http\Controllers\UserTestController::class)->subTests($test->fresh(), $typeTest->fresh());
        $capture = false;
        $this->assertSame($smallReadQueryCount, $readQueryCount);
        $largeSubTests = $largeView->getData()['subTests']->keyBy('id');
        $this->assertSame(4, $largeSubTests->count());
        $this->assertEquals(50, $largeSubTests->get($firstSubTest->id)->progress);
        $this->assertTrue($largeSubTests->get($firstSubTest->id)->completed);
        $this->assertTrue($largeSubTests->get(3)->completed);
        $this->assertFalse($largeSubTests->get(4)->completed);
        $this->assertFalse($largeView->getData()['allSubTestsCompleted']);
        $this->assertSame(40, (int) $largeView->getData()['totalDuration']);
    }

    public function test_admin_attempt_index_whitelists_sort_and_page_size_and_keeps_view_relations(): void
    {
        [$admin] = $this->makeUserWithRole('admin');
        [$test, $typeTest, $subTest] = $this->makeHierarchy('attempt-index');
        $attemptIds = [];

        for ($index = 1; $index <= 12; $index++) {
            $attemptIds[$index] = DB::table('attempts')->insertGetId([
                'user_id' => $admin->id, 'test_id' => $test->id,
                'total_correct' => $index, 'total_question' => 20, 'score' => 100 - $index,
                'created_at' => now()->addMinutes($index), 'updated_at' => now()->addMinutes($index),
            ]);
        }

        $typeAttemptId = DB::table('attempt_type_tests')->insertGetId([
            'attempt_id' => $attemptIds[12], 'type_test_id' => $typeTest->id,
            'correct' => 5, 'total' => 10, 'score' => 50, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('attempt_sub_tests')->insert([
            'attempt_type_test_id' => $typeAttemptId, 'sub_test_id' => $subTest->id,
            'score' => 50, 'correct_count' => 5, 'wrong_count' => 5,
            'is_finished' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($admin);
        $defaultResponse = $this->get(route('admin.attempts.index'));
        $defaultResponse->assertOk()->assertViewHas('attempts', function ($paginator) use ($attemptIds) {
            $first = $paginator->getCollection()->first();
            $typeAttempt = $first->typeTests->first();

            return $paginator->perPage() === 10
                && $first->id === $attemptIds[12]
                && $first->relationLoaded('user')
                && !$first->relationLoaded('tests')
                && !$first->relationLoaded('answers')
                && $typeAttempt->relationLoaded('typeTest')
                && $typeAttempt->relationLoaded('subAnswers')
                && $typeAttempt->subAnswers->first()->relationLoaded('subTest');
        });

        $sortedResponse = $this->get(route('admin.attempts.index', [
            'sort_by' => 'score', 'sort_order' => 'asc', 'per_page' => 20,
        ]));
        $sortedResponse->assertOk()->assertViewHas('attempts', function ($paginator) use ($attemptIds) {
            return $paginator->perPage() === 20
                && $paginator->getCollection()->first()->id === $attemptIds[12];
        });

        $invalidResponse = $this->get(route('admin.attempts.index', [
            'sort_by' => 'does_not_exist', 'sort_order' => 'sideways', 'per_page' => 1000000,
        ]));
        $invalidResponse->assertOk()->assertViewHas('attempts', function ($paginator) use ($attemptIds) {
            return $paginator->perPage() === 10
                && $paginator->getCollection()->first()->id === $attemptIds[12]
                && str_contains($paginator->url(2), 'sort_by=does_not_exist')
                && str_contains($paginator->url(2), 'sort_order=sideways')
                && str_contains($paginator->url(2), 'per_page=1000000');
        });
    }

    public function test_final_statistics_are_recalculated_by_scoring_service_from_subtest_counts(): void
    {
        [$user] = $this->makeUserWithRole(null);
        [$test, $typeA, $subA] = $this->makeHierarchy('final-statistics');
        $subBId = DB::table('sub_tests')->insertGetId([
            'type_tests_id' => $typeA->id, 'name' => 'Second subtest', 'slug' => 'final-statistics-sub-b',
            'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $typeBId = DB::table('type_tests')->insertGetId([
            'tests_id' => $test->id, 'name' => 'Second type', 'slug' => 'final-statistics-type-b',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $subCId = DB::table('sub_tests')->insertGetId([
            'type_tests_id' => $typeBId, 'name' => 'Third subtest', 'slug' => 'final-statistics-sub-c',
            'duration' => 10, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $attempt = Attempt::create([
            'user_id' => $user->id, 'test_id' => $test->id,
            'total_correct' => 999, 'total_question' => 999, 'score' => 999,
        ]);
        $typeAttemptA = AttemptTypeTest::create([
            'attempt_id' => $attempt->id, 'type_test_id' => $typeA->id,
            'correct' => 999, 'total' => 999, 'score' => 999,
        ]);
        $typeAttemptB = AttemptTypeTest::create([
            'attempt_id' => $attempt->id, 'type_test_id' => $typeBId,
            'correct' => 4, 'total' => 5, 'score' => 4,
        ]);
        $subAttemptA = \App\Models\AttemptSubTest::create([
            'attempt_type_test_id' => $typeAttemptA->id, 'sub_test_id' => $subA->id,
            'correct_count' => 3, 'wrong_count' => 2, 'score' => 3,
        ]);
        \App\Models\AttemptSubTest::create([
            'attempt_type_test_id' => $typeAttemptA->id, 'sub_test_id' => $subBId,
            'correct_count' => 2, 'wrong_count' => 1, 'score' => 2,
        ]);
        \App\Models\AttemptSubTest::create([
            'attempt_type_test_id' => $typeAttemptB->id, 'sub_test_id' => $subCId,
            'correct_count' => 4, 'wrong_count' => 1, 'score' => 4,
        ]);

        $controller = app(\App\Http\Controllers\UserTestController::class);
        $statisticsMethod = new \ReflectionMethod($controller, 'updateFinalStatistics');
        $statisticsMethod->invoke($controller, $attempt, $typeAttemptA, $subAttemptA);

        $this->assertSame(3, (int) $subAttemptA->fresh()->correct_count);
        $this->assertSame(2, (int) $subAttemptA->fresh()->wrong_count);
        $this->assertSame(5, (int) $typeAttemptA->fresh()->correct);
        $this->assertSame(8, (int) $typeAttemptA->fresh()->total);
        $this->assertSame(5, (int) $typeAttemptA->fresh()->score);
        $this->assertSame(9, (int) $attempt->fresh()->total_correct);
        $this->assertSame(13, (int) $attempt->fresh()->total_question);
        $this->assertSame(9, (int) $attempt->fresh()->score);
    }

    private function createTables(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique();
            $table->string('password'); $table->timestamp('email_verified_at')->nullable(); $table->timestamps();
        });
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('user_id'); $table->string('name')->nullable(); $table->string('no_id')->nullable();
            $table->date('birth_date')->nullable(); $table->string('gender')->nullable(); $table->string('religion')->nullable();
            $table->string('citizenship')->nullable(); $table->string('fathers_name')->nullable(); $table->string('fathers_occupation')->nullable();
            $table->string('mothers_name')->nullable(); $table->string('mothers_occupation')->nullable(); $table->string('level')->nullable();
            $table->string('school')->nullable(); $table->string('gpa')->nullable(); $table->string('phone_number')->nullable();
            $table->text('province')->nullable(); $table->text('city')->nullable(); $table->text('regency')->nullable();
            $table->text('district')->nullable(); $table->text('address')->nullable(); $table->string('image')->nullable(); $table->timestamps();
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
        Schema::create('subtest_instruction_ranges', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('sub_test_id'); $table->integer('start_number'); $table->integer('end_number');
            $table->integer('order_number')->default(0); $table->boolean('is_active')->default(true);
            $table->text('description')->nullable(); $table->timestamps();
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
