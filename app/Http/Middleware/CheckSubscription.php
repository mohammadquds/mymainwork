<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

  // 1. Skip check if it's the expired page or logout
    if ($request->routeIs('subscription.expired') || $request->is('logout')) {
        return $next($request);
    }

    $user = auth()->user();

 if ($user) {
        $isAdmin = $user->hasRole('Super Admin');
        $isExpired = !$user->end_date || now()->gt($user->end_date);

        // LOGIC: If the user is NOT expired (or is Admin)
        if (!$isExpired || $isAdmin) {

            // If they are currently stuck on the 'expired' page URL...
            if ($request->routeIs('subscription.expired')) {
                // FORCE them back to the home page
                return redirect('/homePage');
            }

            return $next($request);
        }

        // LOGIC: If the user IS expired
        if ($isExpired && !$request->routeIs('subscription.expired')) {
            return redirect()->route('subscription.expired');
        }
    }

    return $next($request);
}
}



