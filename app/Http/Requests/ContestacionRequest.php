<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContestacionRequest extends FormRequest
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
            'archivo_contestacion' => 'required|string|max:500',
            'fecha_notificacion' => 'required|date|max:100',
            'fecha_recepcion' => 'required|date|max:100',
            'observaciones' => 'required|string|max:300',
            ];
    }

      public function attributes()
    {
        return [
            'archivo_contestacion' => 'Contestación',
            'fecha_notificacion' => 'Fecha de notificación',
            'fecha_recepcion' => 'Fecha de recepción',
            'observaciones' => 'Observaciones',
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'presentacion.required_if' => 'Se necesita seleccionar al menos una opción.',
        ];
    }

}
