<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileRequest extends FormRequest
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
            'image' => 'required|file|mimes:jpg,png,webp', // Adjust max size as needed
            'id_card' => 'file|mimes:jpg,png,webp', // Adjust max size as needed
        ];
    }
    public function rulesUpdate()
    {
        return [
            'image' => 'file|mimes:jpg,png,webp', // Adjust max size as needed
            'id_card' => 'file|mimes:jpg,png,webp', // Adjust max size as needed
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
            'image.required' => 'Silakan unggah gambar.',
            'image.file' => 'Format file tidak valid.',
            'image.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, dan WebP.',
            'image.max' => 'Ukuran gambar tidak boleh melebihi 2MB.', // Adjust max size as needed
        ];
    }
}
