<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserInfosRequest;
use App\Http\Requests\EditUserPasswordRequest;
use Illuminate\Http\Request;
use App\Models\User;

class EditUserController extends Controller
{
    function edit($userId)
    {
        $userToEdit = User::findOrFail($userId);
        return view('backend.edit_user')->with('userToEdit', $userToEdit);
    }

    function saveInfos(EditUserInfosRequest $request)
    {
        $userToSave = User::findOrFail($request->id);
        $userToSave->name = $request->name;
        $userToSave->email = $request->email;
        if ($request->id != auth()->user()->id) {
            $userToSave->is_admin = $request->is_admin;
        }
        $userToSave->save();
        return redirect()->route('users_list')->with('success', 'User updated successfully !');
    }

    function savePassword(EditUserPasswordRequest $request)
    {
        $userToSave = User::findOrFail($request->id);
        $userToSave->password = bcrypt($request->new_password);
        $userToSave->save();
        return redirect()->route('users_list')->with('success', 'User\'s password updated successfully !');
    }
}
