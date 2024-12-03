<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccRequest extends FormRequest
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
             'code'=>'required|unique:accs',
             'source_id' => 'required',
             'destination_id' => 'required'
        ];
    }

    static function rulesUpdate($id)
    {
        return [
            'code'=>'required|unique:accs,code,'.$id,
            'source_id' => 'required',
            'destination_id' => 'required'
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
            //
        ];
    }
}
