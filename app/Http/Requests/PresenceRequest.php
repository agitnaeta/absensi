<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresenceRequest extends FormRequest
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
            'in' => 'required',
            'out' => 'required',
            'overtime_in' => 'required',
            'overtime_out' => 'required',
            'is_overtime' => 'required|boolean',
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
            'in.required' => 'Kolom in harus diisi.',
            'in.date_time' => 'Kolom in harus berupa tanggal dan waktu.',
            'out.required' => 'Kolom out harus diisi.',
            'out.date_time' => 'Kolom out harus berupa tanggal dan waktu.',
            'overtime_in.required' => 'Kolom overtime in harus diisi.',
            'overtime_in.date_time' => 'Kolom overtime in harus berupa tanggal dan waktu.',
            'overtime_out.required' => 'Kolom overtime out harus diisi.',
            'overtime_out.date_time' => 'Kolom overtime out harus berupa tanggal dan waktu.',
            'is_overtime.required' => 'Kolom is overtime harus diisi.',
            'is_overtime.boolean' => 'Kolom is overtime harus berupa nilai boolean.',
            'no_record.required' => 'Kolom no record harus diisi.',
            'no_record.boolean' => 'Kolom no record harus berupa nilai boolean.',
        ];
    }
}
