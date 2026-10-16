<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\CarbonImmutable;

class TableUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->select('users.*')
            ->leftJoin('detail_users as user_details', 'user_details.user_id', '=', 'users.id')
            ->with('detail')
            ->whereDoesntHave('roles', function($query) {
                $query->whereIn('name', ['admin', 'super_admin']);
            });

        $search = $request->input('search');
        if (is_string($search) && $search !== '') {
            $term = '%'.mb_strtolower($search).'%';
            $query->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(COALESCE(user_details.name, \'\')) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(users.email) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(COALESCE(user_details.school, \'\')) LIKE ?', [$term]);
            });
        }

        if (is_string($request->input('gender')) && $request->input('gender') !== '') {
            $query->where('user_details.gender', $request->input('gender'));
        }

        if ($request->input('completion_status') === 'complete') {
            $query->whereNotNull('user_details.no_id')->where('user_details.no_id', '!=', '');
        } elseif ($request->input('completion_status') === 'incomplete') {
            $query->where(function ($query) {
                $query->whereNull('user_details.no_id')->orWhere('user_details.no_id', '=', '');
            });
        }

        $religion = $request->input('religion');
        if (is_string($religion) && in_array($religion, ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'], true)) {
            $query->where('user_details.religion', $religion);
        }

        $level = $request->input('level');
        if (is_string($level) && in_array($level, ['SMA', 'SMK', 'MA', 'S1', 'S2', 'S3'], true)) {
            $query->where('user_details.level', $level);
        }

        $today = CarbonImmutable::today();
        $minAge = $request->input('min_age');
        if (is_numeric($minAge) && $minAge !== '') {
            $query->whereDate('user_details.birth_date', '<=', $today->subYears((int) ceil((float) $minAge))->toDateString());
        }

        $maxAge = $request->input('max_age');
        if (is_numeric($maxAge) && $maxAge !== '') {
            $query->whereDate('user_details.birth_date', '>=', $today->subYears((int) floor((float) $maxAge) + 1)->addDay()->toDateString());
        }

        $minGpa = $request->input('min_gpa');
        if ($minGpa !== null && $minGpa !== '') {
            $query->whereNotNull('user_details.gpa')
                ->whereRaw('CAST(user_details.gpa AS DECIMAL(10, 2)) >= ?', [$minGpa]);
        }

        $sortColumns = [
            'name' => 'COALESCE(user_details.name, users.email)',
            'email' => 'users.email',
            'gender' => 'COALESCE(user_details.gender, \'\')',
            'school' => 'COALESCE(user_details.school, \'\')',
            'gpa' => "CAST(COALESCE(NULLIF(user_details.gpa, ''), '0') AS DECIMAL(10, 2))",
            'created_at' => 'users.created_at',
        ];

        $sortBy = $request->input('sort_by', 'created_at');
        if (!is_string($sortBy) || (!array_key_exists($sortBy, $sortColumns) && $sortBy !== 'age')) {
            $sortBy = 'created_at';
        }
        $requestedSortOrder = $request->input('sort_order', 'desc');
        $sortOrder = is_string($requestedSortOrder) ? strtolower($requestedSortOrder) : 'desc';
        $sortOrder = in_array($sortOrder, ['asc', 'desc'], true) ? $sortOrder : 'desc';

        if ($sortBy === 'age') {
            // Age is inverse to birth date; null birth dates had age 0 in the old collection sort.
            $query->orderByRaw('CASE WHEN user_details.birth_date IS NULL THEN 0 ELSE 1 END '.$sortOrder)
                ->orderBy('user_details.birth_date', $sortOrder === 'asc' ? 'desc' : 'asc');
        } else {
            $query->orderByRaw(($sortColumns[$sortBy] ?? $sortColumns['created_at']).' '.$sortOrder);
        }

        $perPage = filter_var($request->input('per_page', 10), FILTER_VALIDATE_INT);
        if ($perPage === false || $perPage < 1) {
            $perPage = 10;
        }

        $users = $query->paginate($perPage)->withQueryString();
        $totalUser = $users->total();
        
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

        $actor = $request->user();
        $targetIsSuperAdmin = $user->hasRole('super_admin');
        $targetIsAdmin = $user->hasRole('admin');

        // Match the dedicated admin-user flow: only super admins may manage admins,
        // and a super-admin account is never deletable through this endpoint.
        if ($targetIsSuperAdmin || ($targetIsAdmin && !$actor?->hasRole('super_admin'))) {
            return $request->ajax()
                ? response()->json(['message' => 'You are not allowed to delete this account'], 403)
                : abort(403);
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
