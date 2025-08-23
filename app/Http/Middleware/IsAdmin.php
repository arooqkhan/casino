<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login'); // if not logged in
        }

        // Check if user has "admin" or "super" role
        if (!Auth::user()->hasAnyRole(['admin', 'super'])) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
