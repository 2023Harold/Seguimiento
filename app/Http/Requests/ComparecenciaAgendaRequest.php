<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaAgendaRequest extends FormRequest
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
            'sala'  => 'required|integer',
            'fecha' => 'required|date|max:10',
            'hora_inicio' => 'required|string|max:6',
            'hora_fin' => 'required|string|max:6',
        ];
    }

    public function attributes()
    {
        return [
            'sala'  => 'sala de reunión',
            'fecha' => 'fecha',
            'hora_inicio' => 'hora de inicio',
            'hora_fin' => 'hora aproximada de término',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya se encuentra registrado.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
            'fecha_oficio_designacion.after_or_equal'=> 'El campo :attribute debe ser posterior o igual a la fecha del acuerdo',
            'fecha_comparecencia.after'=> 'El campo :attribute debe ser posterior a la fecha del acuerdo y a la fecha del oficio'
        ];
    }
}
