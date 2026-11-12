<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    // usage in routes: ->middleware('role:admin')
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role != $role) {
            abort(403, 'You are not allowed to view this page');
        }

        return $next($request);
    }
}
