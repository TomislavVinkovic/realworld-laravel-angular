<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // TODO: SQUASH DATA

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user' => ['required', 'array:email,bio,image'],
            'user.email' => ['nullable', 'email', 'unique:users,email'],
            'user.bio' => ['nullable', 'string'],
            'user.image' => ['nullable', 'image', 'extensions:jpg,jpeg,png,bmp']
        ];
    }
}
