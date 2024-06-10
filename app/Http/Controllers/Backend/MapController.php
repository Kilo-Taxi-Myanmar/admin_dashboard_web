<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(){

        $users = User::role('user')->get();

        // dd($users);
        return view('backend.map.driverlist',compact('users'));
    }


   
}
