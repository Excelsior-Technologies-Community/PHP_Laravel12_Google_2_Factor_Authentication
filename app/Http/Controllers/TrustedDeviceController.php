<?php

namespace App\Http\Controllers;

use App\Models\TrustedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class TrustedDeviceController extends Controller
{
    /**
     * Display a listing of trusted devices for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $rawToken = Cookie::get('trusted_device');
        $currentTokenHash = $rawToken ? hash('sha256', $rawToken) : null;

        $devices = TrustedDevice::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->orderByDesc('last_used_at')
            ->get()
            ->map(function ($device) use ($currentTokenHash) {
                $device->is_current = ($currentTokenHash && $device->device_token === $currentTokenHash);
                return $device;
            });

        $totalDevices = $devices->count();
        $hasOtherDevices = $devices->where('is_current', false)->count() > 0;

        return view('trusted-devices.index', compact('devices', 'totalDevices', 'hasOtherDevices'));
    }

    /**
     * Revoke a single trusted device.
     */
    public function revoke($id)
    {
        $user = Auth::user();
        $device = TrustedDevice::where('user_id', $user->id)->findOrFail($id);

        $rawToken = Cookie::get('trusted_device');
        $currentTokenHash = $rawToken ? hash('sha256', $rawToken) : null;
        $isCurrent = ($currentTokenHash && $device->device_token === $currentTokenHash);

        $device->delete();

        if ($isCurrent) {
            Cookie::queue(Cookie::forget('trusted_device'));
        }

        return back()->with('success', 'Trusted device access has been revoked successfully.');
    }

    /**
     * Revoke all other trusted devices except the current device.
     */
    public function revokeOthers()
    {
        $user = Auth::user();
        $rawToken = Cookie::get('trusted_device');
        $currentTokenHash = $rawToken ? hash('sha256', $rawToken) : null;

        if (!$currentTokenHash) {
            return back()->with('error', 'Cannot identify current device. Revocation aborted.');
        }

        TrustedDevice::where('user_id', $user->id)
            ->where('device_token', '!=', $currentTokenHash)
            ->delete();

        return back()->with('success', 'All other trusted devices have been revoked.');
    }

    /**
     * Revoke all trusted devices.
     */
    public function revokeAll()
    {
        $user = Auth::user();
        TrustedDevice::where('user_id', $user->id)->delete();
        Cookie::queue(Cookie::forget('trusted_device'));

        return back()->with('success', 'All trusted devices have been revoked successfully.');
    }
}
