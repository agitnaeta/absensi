<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'in' => 'required',
            'out' => 'required',
            'over_in' => 'required',
            'over_out' => 'required',
            'day_off' => 'required',
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
            'in.required' => 'Kolom jam masuk harus diisi.',
            'in.date_format' => 'Kolom jam masuk harus memiliki format H:i.',
            'out.required' => 'Kolom jam keluar harus diisi.',
            'out.date_format' => 'Kolom jam keluar harus memiliki format H:i.',
            'over_in.required' => 'Kolom lembur masuk harus diisi.',
            'over_in.date_format' => 'Kolom lembur masuk harus memiliki format H:i.',
            'over_out.required' => 'Kolom lembur keluar harus diisi.',
            'over_out.date_format' => 'Kolom lembur keluar harus memiliki format H:i.',
            'fine_per_minute.required' => 'Kolom denda per menit harus diisi.',
            'fine_per_minute.integer' => 'Kolom denda per menit harus berupa bilangan bulat.',
            'day_off.required' => 'Kolom hari libur harus diisi.',
            'day_off.in' => 'Kolom hari libur harus salah satu dari: :values.',
        ];
    }
}
