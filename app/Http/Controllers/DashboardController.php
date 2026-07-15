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



        return view('dashboard', compact(

            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'twoFactorUsers',
            'users'

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
}
