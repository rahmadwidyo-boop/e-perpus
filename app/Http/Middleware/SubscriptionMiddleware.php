<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->school_id) {
            $school = School::find($user->school_id);

            if ($school && ($school->isExpired() || $school->subscription_status === 'suspended')) {
                if (! $request->routeIs('subscription.*')) {
                    return redirect()->route('subscription.info');
                }
            }
        }

        return $next($request);
    }
}
