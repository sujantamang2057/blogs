<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password as PasswordRule;

class passswordRequest extends FormRequest
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
            // Make current_password required only when provided
            'current_password' => ['sometimes', 'required'],

            // Require new_password with specific rules
            'new_password' => ['required', 'string', 'min:8', PasswordRule::defaults()],

            // Require confirm_password if new_password is present and ensure they match
            'confirm_password' => ['same:new_password'],
        ];
    }
}
