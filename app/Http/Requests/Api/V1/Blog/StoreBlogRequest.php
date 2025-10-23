<?php

namespace App\Http\Requests\Api\V1\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
{    $this->merge([
        'user_id' => $this->user()->id,
    ]);
}


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ["required", "unique:blogs,title", "between:3,20"],
            "category_id" => ["required"],
            "photo" => ["required", "image", "max:2048"],
            "description" => ["nullable", "min:3"],
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'title.unique' => 'Title must be unique',
            'title.between' => 'Title must be between 3 to 20 characters',
            'category_id.required' => 'Category is required',
            'photo.required' => 'Photo is required',
            'photo.image' => 'Photo must be an image',
            'photo.max' => 'Photo size must not exceed 2MB',
            'description.min' => 'Description must be at least 3 characters',
        ];
    }


}
