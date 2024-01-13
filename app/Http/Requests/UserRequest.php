<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'schedule_id' => 'nullable|integer', // Assuming schedule_id is an integer field
            'image' => 'required|file|mimes:jpg,png,webp', // Adjust max size as needed
        ];
    }
    public function updateRules($userId)
    {
        return [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId), // Assuming $userId is the ID of the current user
            ],
            'password' => 'string|nullable',
            'schedule_id' => 'nullable|integer',
            'image' => 'required|file|mimes:jpg,png,webp', // Adjust max size as needed
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Kolom nama harus diisi.',
            'name.string' => 'Kolom nama harus berupa teks.',

            'email.required' => 'Kolom email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',

            'password.required' => 'Kolom password harus diisi.',
            'password.string' => 'Kolom password harus berupa teks.',

            'schedule_id.integer' => 'Kolom schedule_id harus berupa angka.',
            'image.required' => 'Silakan unggah gambar.',
            'image.file' => 'Format file tidak valid.',
            'image.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, dan WebP.',
            'image.max' => 'Ukuran gambar tidak boleh melebihi 2MB.', // Ad
        ];

    }
}
