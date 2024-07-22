<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddNewUserRequest;
use App\Models\User;

class AddUserController extends Controller
{

    function add()
    {
        return view('backend.add_user');
    }

    function store(AddNewUserRequest $request)
    {
        $user = User::Create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => $request->is_admin,
                'password' => bcrypt($request->password)
            ]
        );
        return redirect()->route('users_list')->with('success', 'User added successfully !');
    }
}
