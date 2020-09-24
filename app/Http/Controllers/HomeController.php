<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;


class HomeController extends Controller
{
    //
    public function getAllDatas()
    {

        // A query that will get us all users who is associated with companies in a given country
        // With this query, we can get all the users associated with the companies.
        // I assigned country_id = 1 which corresponds to Canada
        // And to get a date when a user was associated with a company. We will get via a pivot. I set up it in the User model

        $users = User::with(['companies' => function ($query) {
            $query->where('country_id', 1);
        }])->get();

        return response()->json($users);
    }
}
