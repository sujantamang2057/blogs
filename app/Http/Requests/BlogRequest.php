<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $blogId = $this->route('blog'); // Get the post ID from the route for the upadting one the id is taken

        return [

            'title' => [
                'required',
                'max:255',
                // Unique title within the same category
                Rule::unique('blogs')->where(function ($query) {
                    return $query->where('blog_category_id', $this->blog_category_id); //eg;; post_category_id=2(SERACG FOR ALL THE POSTS WITH POST_CAR _ID=2 IF AVAILABLE GIVE THE TRUE FALSE VALUE)
                })->ignore($blogId), //this is used for upadting one as only updating one will have id when url pass so it ignore the condition for that
            ],
            'description' => 'required|string',
            'name' => 'nullable|string',
            // 'image' => 'required|nullable|string',

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
