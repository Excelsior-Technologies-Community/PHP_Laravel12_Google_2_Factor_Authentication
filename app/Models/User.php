<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',

        'status',

        'last_login_at',

        'google2fa_secret',
        'google2fa_enabled',
        'recovery_codes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [

        'email_verified_at' => 'datetime',

        'password' => 'hashed',

        'google2fa_enabled' => 'boolean',

        'recovery_codes' => 'array',

        'last_login_at' => 'datetime',

    ];

    /**
     * Generate new recovery codes
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        $this->recovery_codes = $codes;
        $this->save();

        return $codes;
    }

    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode(string $code): bool
    {
        if (!$this->recovery_codes) {
            return false;
        }

        // Clean the code (remove spaces, convert to uppercase)
        $code = strtoupper(trim($code));

        $key = array_search($code, $this->recovery_codes);

        if ($key !== false) {
            unset($this->recovery_codes[$key]);
            $this->recovery_codes = array_values($this->recovery_codes);
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Verify Google 2FA code
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->google2fa_secret) {
            return false;
        }

        $google2fa = new Google2FA();

        // Clean the code (remove spaces)
        $code = trim($code);

        // Verify the code with a window of 4 (2 before, 2 after current time)
        return $google2fa->verifyKey($this->google2fa_secret, $code, 2);
    }

    /**
     * Generate QR Code URL
     */
    public function getQRCodeUrl(): string
    {
        $google2fa = new Google2FA();
        return $google2fa->getQRCodeUrl(
            config('app.name', 'Laravel'),
            $this->email,
            $this->google2fa_secret
        );
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }
}
