<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;

class CommentStoreRequest extends FormRequest
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
            'comment' => ['required', 'array:body'],
            'comment.body' => 'required|string'
        ];
    }

    public function validated($key = null, $default = null): array {
        return $this->getSquashedData();
    }
    public function safe($key = null, $default = null): ValidatedInput {
        return new ValidatedInput($this->getSquashedData());
    }

    // TODO: Move this to a util class, since it is used in nearly every request
    protected function getSquashedData() {
        $validatedData = parent::validated();
        $articleData = $validatedData['comment'];
        
        return $articleData;
    }
}
