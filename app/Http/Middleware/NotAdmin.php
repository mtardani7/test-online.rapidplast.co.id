<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->hasAnyRole(['admin','super_admin'])) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
