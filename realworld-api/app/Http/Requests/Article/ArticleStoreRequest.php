<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

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
}
