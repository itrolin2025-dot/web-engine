<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // Data staff
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['nullable', 'string', 'max:255'],
            'date_join'     => ['nullable', 'date'],
            'departemen_id' => ['nullable', 'exists:departemens,id'],
            'position'      => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:25'],
            'address'       => ['nullable', 'string'],
            'status'        => ['nullable', 'string'],
            'photo'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // Data user (login)
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        return $rules;
    }
}
