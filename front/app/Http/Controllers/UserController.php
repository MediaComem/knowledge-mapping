<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();
        return view('backend.users')->with('users', $users);
    }
}
