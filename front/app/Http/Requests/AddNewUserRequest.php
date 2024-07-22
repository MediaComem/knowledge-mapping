<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AddNewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string:255',
            'email' => 'required|email|unique:users',
            'is_admin' => 'boolean',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }
}
