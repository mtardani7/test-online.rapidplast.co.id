<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::role('admin')->latest()->get();
        return view('admin.super_admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.super_admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password_mode' => 'required|in:manual,generate',
        ]);

        if ($request->password_mode === 'manual') {
            $request->validate([
                'password' => 'required|min:8'
            ]);
            $password = $request->password;
        } else {
            $request->validate([
                'generated_password' => 'required|min:8'
            ]);
            $password = $request->generated_password;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('admin');

        return redirect()
            ->route('admin-users.index')
            ->with('success', 'Admin berhasil dibuat')
            ->with('generated_password', $password);
    }

    public function edit(User $admin_user)
    {
        return view('admin.super_admin.edit', compact('admin_user'));
    }

    public function update(Request $request, User $admin_user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin_user->id,
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password_mode') && $request->password_mode !== 'no_change') {
            if ($request->password_mode === 'manual') {
                $request->validate([
                    'password' => 'required|min:8'
                ]);
                $password = $request->password;
            } else {
                $request->validate([
                    'generated_password' => 'required|min:8'
                ]);
                $password = $request->generated_password;
            }
            
            $data['password'] = Hash::make($password);
        }

        $admin_user->update($data);

        $message = 'Admin berhasil diperbarui';
        
        if (isset($password)) {
            $message .= '. Password baru telah dibuat';
            return redirect()
                ->route('admin-users.index')
                ->with('success', $message)
                ->with('generated_password', $password);
        }

        return redirect()
            ->route('admin-users.index')
            ->with('success', $message);
    }

    public function destroy(User $admin_user)
    {
        if ($admin_user->hasRole('super_admin')) {
            abort(403);
        }

        $admin_user->delete();

        return redirect()
            ->route('admin-users.index')
            ->with('success', 'Admin berhasil dihapus');
    }
}