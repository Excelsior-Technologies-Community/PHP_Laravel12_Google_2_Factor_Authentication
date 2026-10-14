<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Dashboard Statistics + User Listing
     */
    public function index(Request $request)
    {

        // Dashboard Statistics

        $totalUsers = User::count();

        $activeUsers = User::where('status', 'active')
            ->count();

        $inactiveUsers = User::where('status', 'inactive')
            ->count();


        $twoFactorUsers = User::where('google2fa_enabled', true)
            ->count();



        // User Search + Pagination

        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate(5)
            ->withQueryString();



        $policyEnforced = \Illuminate\Support\Facades\Cache::get('enforce_2fa_policy', false);

        return view('dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'twoFactorUsers',
            'users',
            'policyEnforced'
        ));
    }

    /**
     * Change User Status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return back()->with(
            'success',
            'User status updated successfully'
        );
    }

    /**
     * Toggle Mandatory 2FA Policy for all users
     */
    public function togglePolicy()
    {
        $current = \Illuminate\Support\Facades\Cache::get('enforce_2fa_policy', false);
        \Illuminate\Support\Facades\Cache::forever('enforce_2fa_policy', !$current);
        $statusText = !$current ? 'Enforced' : 'Disabled';

        return back()->with(
            'success',
            "Mandatory 2FA Policy has been {$statusText} successfully."
        );
    }

    /**
     * Admin Emergency 2FA Reset for a locked-out user
     */
    public function emergencyReset2fa(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->recovery_codes = null;
        $user->save();

        // Revoke all trusted devices
        \App\Models\TrustedDevice::where('user_id', $user->id)->delete();

        // Audit Log in Login History
        \App\Models\LoginHistory::create([
            'user_id'      => $user->id,
            'login_method' => '2FA Emergency Reset by Admin (' . auth()->user()->name . ')',
            'status'       => 'Success',
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'logged_in_at' => now(),
        ]);

        return back()->with(
            'success',
            "Two-Factor Authentication for {$user->name} has been reset successfully. They can now login directly with their password."
        );
    }
}
