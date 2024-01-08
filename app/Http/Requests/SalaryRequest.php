<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
            'user_id' => 'required|string',
            'amount' => 'required|integer',
            'overtime_amount' => 'required|integer',
            'overtime_type' => 'required|in:flat,hour',
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
            'user_id.required' => 'Kolom user ID harus diisi.',
            'user_id.string' => 'Kolom user ID harus berupa teks.',
            'amount.required' => 'Kolom amount harus diisi.',
            'amount.integer' => 'Kolom amount harus berupa bilangan bulat.',
            'overtime_amount.required' => 'Kolom overtime amount harus diisi.',
            'overtime_amount.integer' => 'Kolom overtime amount harus berupa bilangan bulat.',
            'overtime_type.required' => 'Kolom overtime type harus diisi.',
            'overtime_type.in' => 'Kolom overtime type harus salah satu dari: :values.',
        ];
    }
}
