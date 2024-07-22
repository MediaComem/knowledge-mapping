<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class DeleteUserController extends Controller
{
    function delete($userId)
    {
        $userToDelete = User::findOrFail($userId);
        if ($userToDelete->id == auth()->user()->id) {
            abort(403, 'You cannot delete yourself');
        }
        $userToDelete->delete();
        return redirect()->route('users_list')->with('success', 'User deleted successfully !');
    }
}
