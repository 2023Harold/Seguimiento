<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatosNotificacionRequest extends FormRequest
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
            'notificacion_estrados' => 'sometimes|nullable|string|in:X',
            'calle' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:100',
            'numero_domicilio' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:10',
            'colonia' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:100',
            'municipio' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:100',
            'entidad_federativa' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:100',
            'codigo_postal' => 'sometimes|nullable|required_without:notificacion_estrados|string|max:5',
            'anexos' => 'required|string|min:2|max:2|in:Si,No',
            'copias_conocimiento' => 'required|string|min:2|max:2|in:Si,No',
        ];
    }

    public function attributes()
    {
        return [
            'notificacion_estrados' => 'notificación por estrados o edictos?',
            'calle' => 'calle',
            'numero_interior' => 'número',
            'colonia' => 'colonia',
            'municipio' => 'municipio',
            'entidad_federativa' => 'entiidad_federativa',
            'fecha_acuse' => 'fecha de acuse',
            'codigo_postal' => 'código postal',
            'anexos' => '¿El requerimiento cuenta con anexos?',
            'copias_conocimiento' => '¿El requerimiento cuenta con copias de conocimiento?',
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
