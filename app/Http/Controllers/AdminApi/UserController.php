<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Use the query builder to construct the SQL query
        $abc = User::role('user');


//        dd($abc);


        return view('backend.users.index', compact('abc'));
    }


}
