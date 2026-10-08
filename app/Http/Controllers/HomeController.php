<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Attempt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $tests = Tests::all();
        $totalUsers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();
        
        $lastMonthUsers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)->count();
        
        if ($lastMonthUsers > 0) {
            $growthPercentage = round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1);
        } else {
            $growthPercentage = $totalUsers > 0 ? 100 : 0;
        }

        $growthColor = $growthPercentage >= 0 ? 'text-green-600' : 'text-red-600';
        $growthIcon = $growthPercentage >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

        $testAttempts = [];
        foreach ($tests as $test) {
            $uniqueAttempts = Attempt::where('test_id', $test->id)
                ->distinct('user_id')
                ->count('user_id');
            
            $testAttempts[$test->id] = $uniqueAttempts;
        }
        $success = session('success');
        $errors = session('errors');
        $oldInput = session('old_input', []);

        return view('admin.home', compact(
            'tests',
            'totalUsers',
            'growthPercentage',
            'growthColor',
            'growthIcon',
            'testAttempts',
            'success',
            'errors',
            'oldInput'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:tests,name',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'type' => 'required|in:TPA,SA,OTHER',
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'rules' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'is_active' => [
                'nullable',
                'boolean'
            ]
        ], [
            'name.required' => 'Nama test wajib diisi',
            'name.min' => 'Nama test minimal 3 karakter',
            'name.max' => 'Nama test maksimal 255 karakter',
            'name.unique' => 'Nama test sudah digunakan',
            'name.regex' => 'Nama test hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'type.required' => 'Jenis test wajib dipilih',
            'type.in' => 'Jenis test tidak valid',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'rules.max' => 'Peraturan maksimal 2000 karakter',
            'is_active.boolean' => 'Status aktif harus berupa true atau false'
        ]);

        if ($validator->fails()) {
            return redirect()->route('test.index')
                ->withErrors($validator)
                ->with('error_type', 'create')
                ->with('old_input', $request->all())
                ->withInput();
        }

        try {
            Tests::create([
                'name' => $request->name,
                'type' => $request->type,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'rules' => $request->rules,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('test.index')
                ->with('success', 'Test berhasil dibuat!')
                ->with('success_type', 'create');

        } catch (\Exception $e) {
            return redirect()->route('test.index')
                ->with('error', 'Terjadi kesalahan saat membuat test. Silakan coba lagi.')
                ->with('error_type', 'create')
                ->with('old_input', $request->all())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $test = Tests::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('tests')->ignore($test->id),
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'type' => 'required|in:TPA,SA,OTHER',
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'rules' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'is_active' => [
                'nullable',
                'boolean'
            ]
        ], [
            'name.required' => 'Nama test wajib diisi',
            'name.min' => 'Nama test minimal 3 karakter',
            'name.max' => 'Nama test maksimal 255 karakter',
            'name.unique' => 'Nama test sudah digunakan',
            'name.regex' => 'Nama test hanya boleh mengandung huruf, angka, spasi, dan tanda hubung',
            'type.required' => 'Jenis test wajib dipilih',
            'type.in' => 'Jenis test tidak valid',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'rules.max' => 'Peraturan maksimal 2000 karakter',
            'is_active.boolean' => 'Status aktif harus berupa true atau false'
        ]);

        if ($validator->fails()) {
            return redirect()->route('test.index')
                ->withErrors($validator)
                ->with('error_type', 'edit_' . $id)
                ->with('old_input', $request->all())
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $test->update([
                'name' => $request->name,
                'type' => $request->type,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'rules' => $request->rules,
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()->route('test.index')
                ->with('success', 'Test berhasil diperbarui!')
                ->with('success_type', 'edit_' . $id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('test.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui test. Silakan coba lagi.')
                ->with('error_type', 'edit_' . $id)
                ->with('old_input', $request->all())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $test = Tests::findOrFail($id);
            $hasAttempts = Attempt::where('test_id', $id)->exists();
            
            if ($hasAttempts) {
                return redirect()->route('test.index')
                    ->with('error', 'Test tidak dapat dihapus karena sudah digunakan dalam attempt.')
                    ->with('error_type', 'delete_' . $id);
            }
            
            DB::beginTransaction();
            $test->delete();
            
            DB::commit();

            return redirect()->route('test.index')
                ->with('success', 'Test berhasil dihapus!')
                ->with('success_type', 'delete');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('test.index')
                ->with('error', 'Test tidak ditemukan.')
                ->with('error_type', 'delete');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('test.index')
                ->with('error', 'Terjadi kesalahan saat menghapus test. Silakan coba lagi.')
                ->with('error_type', 'delete_' . $id);
        }
    }
}
