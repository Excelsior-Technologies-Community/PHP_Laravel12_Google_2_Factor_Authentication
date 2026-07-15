<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{


    /**
     * Delete User
     */
    public function destroy($id)
    {

        $user = User::findOrFail($id);


        $user->delete();


        return back()->with(
            'success',
            'User deleted successfully'
        );

    }



    /**
     * Export Users CSV
     */
    public function export()
    {

        $users = User::all();


        $fileName = 'users.csv';


        $headers = [

            "Content-type"=>"text/csv",

            "Content-Disposition"=>"attachment; filename=$fileName",

            "Pragma"=>"no-cache",

            "Cache-Control"=>"must-revalidate",

            "Expires"=>"0"

        ];



        $callback = function() use($users){


            $file = fopen('php://output','w');


            fputcsv($file,[

                'Name',
                'Email',
                'Status',
                'Last Login'

            ]);



            foreach($users as $user){


                fputcsv($file,[

                    $user->name,

                    $user->email,

                    $user->status,

                    $user->last_login_at

                ]);

            }


            fclose($file);


        };



        return response()->stream(
            $callback,
            200,
            $headers
        );


    }



}