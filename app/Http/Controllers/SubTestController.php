<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Models\TypeTests;
use App\Models\SubTests;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubTestController extends Controller
{
    public function index(Tests $test, TypeTests $typeTest)
    {
        $subTests = $typeTest->subTests()->withCount('questions')->latest()->get();

        return view('admin.sub-tests.index', compact('test', 'typeTest', 'subTests'));
    }

    public function show(Tests $test, TypeTests $typeTest, SubTests $subTest)
    {
        return view('admin.sub-tests.show', compact('test', 'typeTest', 'subTest'));
    }

    public function store(Request $request, Tests $test, TypeTests $typeTest)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('sub_tests')->where(function ($query) use ($typeTest) {
                    return $query->where('type_tests_id', $typeTest->id);
                }),
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                'min:3'
            ],
            'description' => [
                'nullable',
                'max:500'
            ],
            'duration' => [
                'nullable',
                'integer',
                'min:1',
                'max:300'
            ]
        ], [
            'name.required' => 'Nama list test wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'name.unique' => 'Nama list test sudah digunakan di sub test ini',
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'name.min' => 'Nama minimal 3 karakter',
            'description.max' => 'Deskripsi maksimal 500 karakter',
            'duration.integer' => 'Durasi harus berupa angka',
            'duration.min' => 'Durasi minimal 1 menit',
            'duration.max' => 'Durasi maksimal 300 menit (5 jam)'
        ]);

        $typeTest->subTests()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'] ?? 10,
        ]);

        return redirect()
            ->route('sub-test.index', [$test->slug, $typeTest->slug])
            ->with('success', 'List Test berhasil ditambahkan.');
    }

    public function update(Request $request, Tests $test, TypeTests $typeTest, $id)
    {
        $subTest = $typeTest->subTests()->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('sub_tests')->where(function ($query) use ($typeTest) {
                    return $query->where('type_tests_id', $typeTest->id);
                })->ignore($subTest->id),
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                'min:3'
            ],
            'description' => [
                'nullable',
                'max:500'
            ],
            'duration' => [
                'nullable',
                'integer',
                'min:1',
                'max:300'
            ]
        ], [
            'name.required' => 'Nama list test wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'name.unique' => 'Nama list test sudah digunakan di sub test ini',
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'name.min' => 'Nama minimal 3 karakter',
            'description.max' => 'Deskripsi maksimal 500 karakter',
            'duration.integer' => 'Durasi harus berupa angka',
            'duration.min' => 'Durasi minimal 1 menit',
            'duration.max' => 'Durasi maksimal 300 menit (5 jam)'
        ]);

        $subTest->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'] ?? $subTest->duration,
        ]);

        return redirect()
            ->route('sub-test.index', [$test->slug, $typeTest->slug])
            ->with('success', 'List Test berhasil diperbarui.');
    }

    public function destroy(Tests $test, TypeTests $typeTest, $id)
    {
        $typeTest->subTests()->findOrFail($id)->delete();

        return redirect()->route('sub-test.index', [$test->slug, $typeTest->slug])->with('success', 'Sub Test berhasil dihapus');
    }
}