<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
    class UpdateUserRequest extends FormRequest {
        public function rules() {
        return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $this->user->id,
        'password' => 'nullable|min:6'
        ];
    }
}