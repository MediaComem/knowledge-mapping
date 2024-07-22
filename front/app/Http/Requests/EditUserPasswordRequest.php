<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class EditUserPasswordRequest extends FormRequest
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
        $validationArray =
            [
                'id' => 'required|integer|exists:users,id',
                'new_password' => [
                    'required',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                ],
                'password_confirmation' => 'required|same:new_password'
            ];
        if (Auth::user()->id == $this->id) {
            $validationArray['password'] = 'required|current_password';
        }

        return $validationArray;
    }
}
