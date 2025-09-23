<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;

class ArticleStoreRequest extends FormRequest
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
            'article' => ['required', 'array:title,description,body,tagList'],
            'article.title' => ['required', 'string'],
            'article.description' => ['required', 'string'],
            'article.body' => ['required', 'string'],
            'article.tagList' => ['nullable', 'array']
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
        $userData = $validatedData['article'];
        
        return $userData;
    }
}
