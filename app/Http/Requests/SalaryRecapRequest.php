<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRecapRequest extends FormRequest
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
            'recap_month' => 'required|string',
            'work_day' => 'nullable|integer',
            'late_day' => 'nullable|integer',
            'salary_amount' => 'nullable|integer',
            'overtime_amount' => 'nullable|integer',
            'loan_cut' => 'nullable|integer',
            'late_cut' => 'nullable|integer',
            'abstain_cut' => 'nullable|integer',
            'received' => 'nullable|integer',
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
            'recap_month.required' => 'Kolom recap month harus diisi.',
            'recap_month.string' => 'Kolom recap month harus berupa teks.',
            'work_day.integer' => 'Kolom work day harus berupa bilangan bulat.',
            'late_day.integer' => 'Kolom late day harus berupa bilangan bulat.',
            'salary_amount.integer' => 'Kolom salary amount harus berupa bilangan bulat.',
            'overtime_amount.integer' => 'Kolom overtime amount harus berupa bilangan bulat.',
            'loan_cut.integer' => 'Kolom loan cut harus berupa bilangan bulat.',
            'late_cut.integer' => 'Kolom late cut harus berupa bilangan bulat.',
            'abstain_cut.integer' => 'Kolom abstain cut harus berupa bilangan bulat.',
            'received.integer' => 'Kolom received harus berupa bilangan bulat.',
        ];
    }
}
