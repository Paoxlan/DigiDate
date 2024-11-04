<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()?->user();
        if (!$user) return redirect()->route('login');
        if (!app()->hasDebugModeEnabled() && !$user->two_factor_confirmed_at)
            return redirect()->route('register.two-factor');

        return $next($request);
    }
}
