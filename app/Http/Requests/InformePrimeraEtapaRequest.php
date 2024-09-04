<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformePrimeraEtapaRequest extends FormRequest
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
            'numero_informe' => 'required|string|max:100',
            'fecha_informe' => 'required|date_format:Y-m-d|max:10',
            'informe' => 'required|string|max:100',
        ];
    }    
        public function attributes()
    {
        return [
           'numero_informe' => 'número del informe de auditoría',
           'fecha_informe' => 'fecha del informe de auditoría',
           'informe' => 'informe de auditoría',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            // 'unique' => 'El :attribute ya se encuentra registrado.',
            // 'required_if' => 'El campo :attribute es obligatorio.',
            // 'required_without' => 'El campo :attribute es obligatorio.',

        ];
    }
}
