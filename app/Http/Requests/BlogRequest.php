<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'name' => 'nullable|string',
            'image' => 'required|nullable|string',

            'blog_category_id' => 'exists:blog_categories,id',

            //
        ];
    }

    protected function prepareForValidation()
    {
        // Define sanitization rules
        $sanitizer = new Sanitizer($this->all(), [
            'title' => 'trim|escape',

        ]);

        // Replace request data with sanitized data
        $this->merge($sanitizer->sanitize());
    }
}
