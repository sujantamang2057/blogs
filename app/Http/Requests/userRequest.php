<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class userRequest extends FormRequest
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

        // Determine if we are updating an existing user or creating a new one
        $userId = $this->route('user') ? $this->route('user') : null;
        $isUpdating = $userId !== null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone' => [
                'required',
                'string',
                'regex:/^(\+977-?\d{2}-?\d{8}|\d{10})$/',  // Allow +977 or just 10 digits
                // Unique phone number
            ],            // 'address' => ['nullable', 'string', 'max:500'], // Limiting the address to 500 characters

            'image' => ['nullable', 'string'],

            // Password is required when creating, but optional on update
            'password' => [$isUpdating ? 'nullable' : 'required', Rules\Password::defaults()],
        ];

    }
}
