<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaAcuseRequest extends FormRequest
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
            'oficio_recepcion' => 'required|string|max:100',
            'fecha_recepcion' => 'required|date_format:Y-m-d|max:10',
            'oficio_acuse' => 'required|string|max:100',
            'fecha_acuse' => 'required|date_format:Y-m-d|max:10',
        ];
    }

    public function attributes()
    {
        return [
            'oficio_recepcion' => 'comprobante de recepción depto. de notificaciones',
            'fecha_recepcion' => 'fecha de recepción',
            'oficio_acuse' => 'acuse del oficio de la comparecencia',
            'fecha_acuse' => 'fecha del acuse',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya se encuentra registrado.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
        ];
    }
}
