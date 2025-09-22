<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user' => ['required', 'array:username,email,password'],
            'user.username' => ['required', 'unique:users,name'],
            'user.email' => ['required', 'unique:users,email'],
            'user.password' => [
                'required', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated();
        $userData = $validatedData['user'];
        
        $userData['name'] = $userData['username'];
        unset($userData['username']);

        return $userData;
    }
}
