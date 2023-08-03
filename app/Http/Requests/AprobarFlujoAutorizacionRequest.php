<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprobarFlujoAutorizacionRequest extends FormRequest
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
            'estatus' => 'required|string|max:50',
            'motivo_rechazo' => 'required_if:estatus,Rechazado|string|nullable|max:1000',
        ];
    }

    public function attributes()
    {
        return [
            'estatus' => '',
            'motivo_rechazo' => 'motivo del rechazo',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute de tener máximo :max caracteres.',
        ];
    }
}
