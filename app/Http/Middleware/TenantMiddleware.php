<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $schoolId = Auth::user()?->school_id;
        if ($schoolId) {
            app()->instance('current_school_id', $schoolId);
        }
        return $next($request);
    }
}
