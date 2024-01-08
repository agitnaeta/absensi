<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
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
            'date' => 'required|date',
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
            'date.required' => 'Kolom date harus diisi.',
            'date.date' => 'Kolom date harus berupa tanggal.',
        ];
    }
}
