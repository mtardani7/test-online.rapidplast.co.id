<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Models\TypeTests;
use App\Models\SubTests;
use App\Models\Questions;
use App\Models\QuestionChoices;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class QuestionController extends Controller
{
    private function assertHierarchy(Tests $test, TypeTests $typeTest, SubTests $subTest): void
    {
        abort_unless((int) $typeTest->tests_id === (int) $test->id, 404);
        abort_unless((int) $subTest->type_tests_id === (int) $typeTest->id, 404);
    }

    private function questionInSubTest(SubTests $subTest, Questions $question): Questions
    {
        return $subTest->questions()->whereKey($question->getKey())->firstOrFail();
    }

    public function index(Tests $test, TypeTests $typeTest, SubTests $subTest, Request $request)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);

        $query = $subTest->questions()->with('choices');
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question_text', 'LIKE', "%{$search}%")
                  ->orWhere('question_optional', 'LIKE', "%{$search}%")
                  ->orWhereHas('choices', function($q2) use ($search) {
                      $q2->where('option_text', 'LIKE', "%{$search}%");
                  });
            });
        }
        if ($request->has('has_image')) {
            if ($request->has_image == 'yes') {
                $query->whereNotNull('image');
            } elseif ($request->has_image == 'no') {
                $query->whereNull('image');
            }
        }
        if ($request->has('has_correct')) {
            if ($request->has_correct == 'yes') {
                $query->whereNotNull('correct_option_id');
            } elseif ($request->has_correct == 'no') {
                $query->whereNull('correct_option_id');
            }
        }
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        $questions = $query->paginate(10)->withQueryString();
        $totalQuestions = $subTest->questions()->count();
        $questionsWithImage = $subTest->questions()->whereNotNull('image')->count();
        $totalChoices = QuestionChoices::whereIn(
            QuestionChoices::questionForeignKey(),
            $subTest->questions()->pluck('id')
        )->count();
        $avgChoices = $totalQuestions > 0 ? round($totalChoices / $totalQuestions, 1) : 0;
        
        return view('admin.question.index', compact(
            'test', 
            'typeTest', 
            'subTest', 
            'questions',
            'totalQuestions',
            'questionsWithImage',
            'avgChoices'
        ));
    }

    public function store(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);

        $request->validate([
            'question_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'question_optional' => 'nullable|string',
            'options' => 'nullable|array',
            'correct' => 'nullable'   
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $uploaded = cloudinary()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'questions',
                    'transformation' => [
                        'width' => 1200,
                        'height' => 1200,
                        'crop' => 'limit',  
                        'quality' => 'auto', 
                        'fetch_format' => 'auto'
                    ]
                ]
            );

            $imageUrl = $uploaded->getSecurePath();
        }

        $question = Questions::create([
            'sub_tests_id' => $subTest->id,
            'question_text' => $request->question_text,
            'image' => $imageUrl,
            'question_optional' => $request->question_optional,
            'correct_option_id' => null
        ]);

        $correctOptionId = null;

        if ($request->options) {
            foreach ($request->options as $key => $value) {

                $option = $question->choices()->create([
                    'option_label' => $key ?? '',      
                    'option_text'  => $value ?? ''     
                ]);

                if ($request->correct !== null && $key == $request->correct) {
                    $correctOptionId = $option->id;
                }
            }
        }

        $question->update(['correct_option_id' => $correctOptionId]);

        return redirect()->route('question.index', [$test->slug, $typeTest->slug, $subTest->slug])->with('success', 'Soal berhasil ditambahkan');
    }
    private function extractCloudinaryPublicId(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = preg_replace('#^/.*/upload/v[0-9]+/#', '', $path);
        return pathinfo($path, PATHINFO_FILENAME)
            ? dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME)
            : '';
    }

    public function update(Request $request, Tests $test, TypeTests $typeTest, SubTests $subTest, Questions $question) 
    {
        $this->assertHierarchy($test, $typeTest, $subTest);
        $question = $this->questionInSubTest($subTest, $question);

        $validated = $request->validate([
            'question_text' => [
                'required',
                'max:1000'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048'
            ],
            'question_optional' => [
                'nullable',
                'max:500'
            ],
            'options' => [
                'required',
                'array',
                'min:2',
                'max:6'
            ],
            'options.*' => [
                'required',
                'max:255'
            ],
            'correct' => [
                'required',
                'string'
            ]
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi',
            'question_text.max' => 'Pertanyaan maksimal 1000 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, GIF, atau WebP',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'question_optional.max' => 'Keterangan maksimal 500 karakter',
            'options.required' => 'Opsi jawaban wajib diisi',
            'options.min' => 'Minimal 2 opsi jawaban',
            'options.max' => 'Maksimal 6 opsi jawaban',
            'options.*.required' => 'Teks opsi wajib diisi',
            'options.*.max' => 'Teks opsi maksimal 255 karakter',
            'correct.required' => 'Pilih jawaban yang benar'
        ]);
        $imageUrl = $question->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                if ($question->image) {
                    $publicId = $this->extractCloudinaryPublicId($question->image);
                    Cloudinary::destroy($publicId, [
                        'resource_type' => 'image',
                        'invalidate' => true
                    ]);
                }
                $uploaded = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'questions',
                        'transformation' => [
                            'width' => 1200,
                            'height' => 1200,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto'
                        ]
                    ]
                );
                $imageUrl = $uploaded->getSecurePath();
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Gagal mengupdate gambar: ' . $e->getMessage());
            }
        }
        $question->update([
            'question_text' => $validated['question_text'],
            'image' => $imageUrl,
            'question_optional' => $validated['question_optional'] ?? null,
        ]);
        $question->choices()->delete();
        $correctOptionId = null;
        foreach ($validated['options'] as $label => $text) {
            $option = $question->choices()->create([
                'option_label' => $label,
                'option_text' => $text
            ]);
            
            if ($label === $validated['correct']) {
                $correctOptionId = $option->id;
            }
        }
        $question->update(['correct_option_id' => $correctOptionId]);

        return back()->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy(Tests $test, TypeTests $typeTest, SubTests $subTest, Questions $question)
    {
        $this->assertHierarchy($test, $typeTest, $subTest);
        $question = $this->questionInSubTest($subTest, $question);

        try {
            if ($question->image) {
                $publicId = $this->extractCloudinaryPublicId($question->image);
                Cloudinary::destroy($publicId, [
                    'resource_type' => 'image',
                    'invalidate' => true
                ]);
            }
            $question->choices()->delete();
            $question->delete();

            return back()->with('success', 'Soal berhasil dihapus');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus soal: ' . $e->getMessage());
        }
    }
}
