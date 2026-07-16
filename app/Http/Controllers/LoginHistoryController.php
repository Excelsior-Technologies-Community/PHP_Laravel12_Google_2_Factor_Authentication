<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    /**
     * Authentication History
     */
    public function index(Request $request)
    {
        $query = LoginHistory::with('user');

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Login Method Filter
        if ($request->filled('method')) {

            $query->where(
                'login_method',
                $request->method
            );
        }

        // Status Filter
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $loginHistories = $query
            ->latest('logged_in_at')
            ->paginate(5)
            ->withQueryString();

        // Statistics
        $totalLogins = LoginHistory::count();

        $trustedLogins = LoginHistory::where(
            'login_method',
            'Trusted Device'
        )->count();

        $recoveryLogins = LoginHistory::where(
            'login_method',
            'Recovery Code'
        )->count();

        $failedLogins = LoginHistory::where(
            'status',
            'Failed'
        )->count();

        return view(
            'login-history.index',
            compact(
                'loginHistories',
                'totalLogins',
                'trustedLogins',
                'recoveryLogins',
                'failedLogins'
            )
        );
    }
}
