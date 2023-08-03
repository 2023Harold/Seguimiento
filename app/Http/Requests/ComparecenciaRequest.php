<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaRequest extends FormRequest
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
            'nombre_titular' => 'required|string|max:120',
            'cargo_titular' => 'required|string|max:120',
            'oficio_comparecencia' => 'required|string|max:100',
            'fecha_comparecencia' => 'required|date',
            'hora_comparecencia_inicio' => 'required|string|max:15',
            'hora_comparecencia_termino' => 'required|string|max:15',
            'fecha_inicio_aclaracion' => 'required',
            'fecha_termino_aclaracion' => 'required',           
        ];
    }

    public function attributes()
    {
        return [
            'nombre_titular' => 'nombre del titular  a quien se dirige la comparecencia',
            'cargo_titular' => 'cargo del titular a quien se dirige la comparecencia',
            'oficio_comparecencia' => 'oficio de notificación de la comparecencia',
            'fecha_comparecencia' => 'fecha de la comparecencia',
            'hora_comparecencia_inicio' => 'hora de inicio de la comparecencia',
            'hora_comparecencia_termino' => 'hora de término de la comparecencia',
            'fecha_inicio_aclaracion' => 'inicio de la etapa de aclaración',
            'fecha_termino_aclaracion' => 'término de la etapa de aclaración',      
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
