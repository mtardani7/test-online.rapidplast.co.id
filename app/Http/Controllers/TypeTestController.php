<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Models\TypeTests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TypeTestController extends Controller
{
    public function index(Tests $test)
    {
        $typeTests = $test->typeTests()->latest()->get();

        return view('admin.type-test.index', compact('test', 'typeTests'));
    }

    public function store(Request $request, Tests $test)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('type_tests')->where(function ($query) use ($test) {
                    return $query->where('tests_id', $test->id);
                }),
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                'min:3'
            ],
            'description' => [
                'required',
                'max:1000'
            ]
        ], [
            'name.required' => 'Nama sub test wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'name.unique' => 'Nama sub test sudah digunakan di test ini',
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'name.min' => 'Nama minimal 3 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 1000 karakter'
        ]);

        TypeTests::create([
            'tests_id' => $test->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description']
        ]);

        return redirect()
            ->route('type-test.index', $test->slug)
            ->with('success', 'Sub Test berhasil ditambahkan.');
    }

    public function update(Request $request, Tests $test, TypeTests $typeTest)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('type_tests')->where(function ($query) use ($test) {
                    return $query->where('tests_id', $test->id);
                })->ignore($typeTest->id),
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                'min:3'
            ],
            'description' => [
                'required',
                'max:1000'
            ]
        ], [
            'name.required' => 'Nama sub test wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'name.unique' => 'Nama sub test sudah digunakan di test ini',
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'name.min' => 'Nama minimal 3 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 1000 karakter'
        ]);

        $typeTest->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description']
        ]);

        return redirect()
            ->route('type-test.index', $test->slug)
            ->with('success', 'Sub Test berhasil diperbarui.');
    }

    public function destroy(Tests $test, TypeTests $typeTest)
    {
        $typeTest->delete();

        return redirect()->route('type-test.index', $test)->with('success', 'Type Test berhasil dihapus.');
    }
}