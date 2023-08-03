<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaRespuestaRequest extends FormRequest
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
            'oficio_respuesta' => 'required|string|max:100',
            'fecha_respuesta' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'oficio_respuesta' => 'oficio de respuesta de la Entidad Fiscalizable',
            'fecha_respuesta' => 'fecha de recibido',
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
