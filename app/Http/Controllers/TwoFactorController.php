<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

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
            return redirect()->intended(route('dashboard'));
        }

        // Try recovery code
        if ($user->verifyRecoveryCode($request->code)) {
            Auth::login($user);
            session()->forget('2fa:user:id');
            session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('warning', 'You have used a recovery code. Please generate new recovery codes.');
        }

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
}