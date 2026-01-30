<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->user()->id;
        return [
            'user' => ['required', 'array:email,bio,image'],
            'user.email' => [
                'nullable', 
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'user.bio' => ['nullable', 'string'],
            'user.image' => ['nullable', 'image', 'extensions:jpg,jpeg,png,bmp']
        ];
    }

    public function validated($key = null, $default = null): array {
        return $this->getSquashedData();
    }

    public function safe($key = null, $default = null): ValidatedInput {
        return new ValidatedInput($this->getSquashedData());
    }

    protected function getSquashedData() {
        $validatedData = parent::validated();
        $userData = $validatedData['user'];
        
        return $userData;
    }
}
