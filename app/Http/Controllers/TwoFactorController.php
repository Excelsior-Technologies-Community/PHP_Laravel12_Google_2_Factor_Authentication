<?php

namespace App\Http\Controllers;

use App\Models\TrustedDevice;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Models\LoginHistory;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup form
     */
    public function showSetupForm()
    {
        $user = Auth::user();

        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard')->with('info', '2FA is already enabled.');
        }

        // Generate secret if not exists
        if (!$user->google2fa_secret) {
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        // Generate QR Code URL
        $google2fa = new Google2FA();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'Laravel'),
            $user->email,
            $user->google2fa_secret
        );

        // Generate QR Code SVG
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        // Generate recovery codes
        $recoveryCodes = $user->recovery_codes ?: $user->generateRecoveryCodes();

        return view('auth.two-factor-setup', [
            'qrCode' => $qrCodeSvg,
            'secret' => $user->google2fa_secret,
            'recoveryCodes' => $recoveryCodes
        ]);
    }

    /**
     * Enable 2FA
     */
    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($user->verifyTwoFactorCode($request->code)) {
            $user->google2fa_enabled = true;
            $user->save();

            return redirect()->route('dashboard')
                ->with('success', 'Two-factor authentication has been enabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    /**
     * Disable 2FA
     */
    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->recovery_codes = null;
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Show 2FA verification form
     */
    public function showVerificationForm()
    {
        if (!session('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-verify');
    }

    /**
     * Verify 2FA code
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = session('2fa:user:id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Try verification code first
        if ($user->verifyTwoFactorCode($request->code)) {

            Auth::login($user);

            session()->forget('2fa:user:id');

            session()->regenerate();

            if ($request->filled('remember_device')) {
                $this->rememberTrustedDevice($user, $request);
            }

            $this->storeLoginHistory(
                $user,
                $request,
                'Password + 2FA',
                'Success'
            );

            return redirect()->intended(route('dashboard'));
        }

        // Try recovery code
        if ($user->verifyRecoveryCode($request->code)) {

            Auth::login($user);

            session()->forget('2fa:user:id');

            session()->regenerate();

            if ($request->filled('remember_device')) {
                $this->rememberTrustedDevice($user, $request);
            }

            $this->storeLoginHistory(
                $user,
                $request,
                'Recovery Code',
                'Success'
            );

            return redirect()->intended(route('dashboard'))
                ->with(
                    'warning',
                    'You have used a recovery code. Please generate new recovery codes.'
                );
        }

        $this->storeLoginHistory(
            $user,
            $request,
            'Password + 2FA',
            'Failed'
        );

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = $user->recovery_codes;

        if (!$recoveryCodes) {
            $recoveryCodes = $user->generateRecoveryCodes();
        }

        return view('auth.two-factor-recovery', compact('recoveryCodes'));
    }

    /**
     * Generate new recovery codes
     */
    public function generateNewRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = $user->generateRecoveryCodes();

        return redirect()->route('2fa.recovery')
            ->with('success', 'New recovery codes have been generated.')
            ->with('recoveryCodes', $recoveryCodes);
    }

    /**
     * Store a trusted device for the user.
     */
    private function rememberTrustedDevice(User $user, Request $request): void
    {
        $token = Str::random(64);

        TrustedDevice::create([
            'user_id'      => $user->id,
            'device_token' => hash('sha256', $token),

            // Save browser user-agent instead of using an external package
            'device_name'  => substr($request->userAgent() ?? 'Unknown Device', 0, 120),

            'browser'      => $request->header('User-Agent'),

            'platform'     => php_uname('s'),

            'ip_address'   => $request->ip(),

            'last_used_at' => now(),

            'expires_at'   => now()->addDays(30),
        ]);

        Cookie::queue(
            Cookie::make(
                'trusted_device',
                $token,
                60 * 24 * 30,   // 30 days
                '/',
                null,
                app()->environment('production'),
                true,
                false,
                'Lax'
            )
        );
    }

    /**
     * Store login history
     */
    private function storeLoginHistory(
        User $user,
        Request $request,
        string $method,
        string $status
    ): void {

        LoginHistory::create([
            'user_id'      => $user->id,
            'login_method' => $method,
            'status'       => $status,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'logged_in_at' => now(),
        ]);
    }
}
