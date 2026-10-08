<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class TableUserController extends Controller
{
    public function index(Request $request)
    {
        $allUsers = User::with(['detail', 'roles'])
            ->whereDoesntHave('roles', function($query) {
                $query->whereIn('name', ['admin', 'super_admin']);
            })
            ->get();
        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search);
            $allUsers = $allUsers->filter(function($user) use ($searchTerm) {
                $name = strtolower(optional($user->detail)->name ?? '');
                $email = strtolower($user->email);
                $school = strtolower(optional($user->detail)->school ?? '');
                
                return str_contains($name, $searchTerm) || 
                       str_contains($email, $searchTerm) || 
                       str_contains($school, $searchTerm);
            });
        }
        
        if ($request->has('gender') && $request->gender != '') {
            $allUsers = $allUsers->filter(function($user) use ($request) {
                return optional($user->detail)->gender == $request->gender;
            });
        }
        
        if ($request->has('min_age') && $request->min_age != '') {
            $allUsers = $allUsers->filter(function($user) use ($request) {
                $birthDate = optional($user->detail)->birth_date;
                if (!$birthDate) return false;
                
                $age = \Carbon\Carbon::parse($birthDate)->age;
                return $age >= $request->min_age;
            });
        }
        
        if ($request->has('max_age') && $request->max_age != '') {
            $allUsers = $allUsers->filter(function($user) use ($request) {
                $birthDate = optional($user->detail)->birth_date;
                if (!$birthDate) return false;
                
                $age = \Carbon\Carbon::parse($birthDate)->age;
                return $age <= $request->max_age;
            });
        }
        if ($request->has('min_gpa') && $request->min_gpa != '') {
            $allUsers = $allUsers->filter(function($user) use ($request) {
                $gpa = optional($user->detail)->gpa;
                return $gpa && $gpa >= $request->min_gpa;
            });
        }
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allUsers = $allUsers->sortBy(function($user) use ($sortBy, $sortOrder) {
            switch($sortBy) {
                case 'name':
                    return optional($user->detail)->name ?? $user->email;
                case 'email':
                    return $user->email;
                case 'gender':
                    return optional($user->detail)->gender ?? '';
                case 'age':
                    $birthDate = optional($user->detail)->birth_date;
                    return $birthDate ? \Carbon\Carbon::parse($birthDate)->age : 0;
                case 'school':
                    return optional($user->detail)->school ?? '';
                case 'gpa':
                    return optional($user->detail)->gpa ?? 0;
                default:
                    return $user->created_at;
            }
        }, SORT_REGULAR, $sortOrder === 'desc');
        $perPage = $request->get('per_page', 10);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $users = new LengthAwarePaginator(
            $currentItems,
            $allUsers->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );
        
        $totalUser = $allUsers->count();
        
        return view('users', compact('users', 'totalUser'));
    }
    
    public function destroy(Request $request, $id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return $request->ajax()
                ? response()->json(['message' => 'User not found'], 404)
                : redirect()->back()->withErrors('User not found');
        }

        // prevent deleting admin accounts
        if ($user->roles()->where('name', 'admin')->exists()) {
            return $request->ajax()
                ? response()->json(['message' => 'Cannot delete admin user'], 403)
                : redirect()->back()->withErrors('Cannot delete admin user');
        }

        try {
            $user->delete();
            if ($request->ajax()) {
                return response()->json(['message' => 'User deleted']);
            }
            return redirect()->back()->with('success', 'User deleted');
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['message' => 'Delete failed'], 500)
                : redirect()->back()->withErrors('Delete failed');
        }
    }
}