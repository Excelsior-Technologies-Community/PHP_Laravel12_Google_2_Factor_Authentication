<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnforceTwoFactorPolicy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $policyEnforced = Cache::get('enforce_2fa_policy', false);

        if ($policyEnforced && Auth::check()) {
            $user = Auth::user();

            if (!$user->google2fa_enabled) {
                // Allowed route names during mandatory setup
                $allowedRoutes = [
                    '2fa.setup',
                    '2fa.enable',
                    '2fa.recovery',
                    'logout',
                    'profile.destroy',
                ];

                $currentRoute = $request->route() ? $request->route()->getName() : null;

                if (!in_array($currentRoute, $allowedRoutes)) {
                    return redirect()->route('2fa.setup')->with(
                        'warning',
                        'Security Policy Notice: Two-Factor Authentication (2FA) is mandatory for your account. Please complete your 2FA setup to continue.'
                    );
                }
            }
        }

        return $next($request);
    }
}
