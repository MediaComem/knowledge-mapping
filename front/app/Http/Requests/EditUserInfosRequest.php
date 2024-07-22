<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Http\FormRequest;

class EditUserInfosRequest extends FormRequest
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
                'name' => 'required|string:255',
                'email' => 'required|email|unique:users,' . $this->id,
            ];
        if (Auth::user()->id == $this->id) {
            $validationArray['is_admin'] = 'boolean';
        }

        return $validationArray;
    }
}
