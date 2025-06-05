<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PliegosObservacionContestacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
       public function rules()
    {
        return [
            'oficio_contestacion'=>'required|string|max:100',
            'foliocrr_id'=>'required|sometimes|integer',
        ];
    }

    public function attributes()
    {
        return [
            'oficio_contestacion'=>'oficio de contestación del pliego',
            'foliocrr_id'=>'folio de correspondencia',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }
}
