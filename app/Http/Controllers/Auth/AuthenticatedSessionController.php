<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TrustedDevice;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\LoginHistory;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        /*
    |--------------------------------------------------------------------------
    | Trusted Device Check
    |--------------------------------------------------------------------------
    */
        if ($user->google2fa_enabled) {

            $trustedToken = Cookie::get('trusted_device');

            if ($trustedToken) {

                $trustedDevice = TrustedDevice::where('user_id', $user->id)
                    ->where('device_token', hash('sha256', $trustedToken))
                    ->where('expires_at', '>', now())
                    ->first();

                if ($trustedDevice) {

                    $trustedDevice->update([
                        'last_used_at' => now(),
                    ]);

                    LoginHistory::create([
                        'user_id'      => $user->id,
                        'login_method' => 'Trusted Device',
                        'status'       => 'Success',
                        'ip_address'   => $request->ip(),
                        'user_agent'   => $request->userAgent(),
                        'logged_in_at' => now(),
                    ]);

                    $request->session()->regenerate();

                    return redirect()->intended(route('dashboard', absolute: false));
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Normal 2FA Flow
        |--------------------------------------------------------------------------
        */

            Auth::logout();

            session()->put('2fa:user:id', $user->id);

            return redirect()->route('2fa.verify');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
