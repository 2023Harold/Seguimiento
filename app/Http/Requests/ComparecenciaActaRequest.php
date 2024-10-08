<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaActaRequest extends FormRequest
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
            'oficio_acta' => 'required|string|max:100',
            'numero_acta' => 'required|string|max:50',
            'fecha_acta' => 'required|date_format:Y-m-d|max:10',
            'hora_comparecencia_termino' => 'sometimes|nullable|required_if:comparecio,X|string|max:15',      
            //'hora_comparecencia_termino' => 'hora de término de la comparecencia',
            'nombre_representante'=> 'sometimes|nullable|string|required_if:comparecio,X|max:100',
            'cargo_representante1'=> 'sometimes|nullable|required_if:comparecio,X|string|max:300',
            'tipo_identificacion'=>'sometimes|string|required_if:comparecio,X|max:100',
            'numero_identificacion_representante'=> 'sometimes|nullable|required_if:comparecio,X|string|max:100',

        ];
    }

    public function attributes()
    {
        return [
            'oficio_acta' => 'acta de comparecencia',
            'numero_acta' => 'número de acta',
            'fecha_acta' => 'fecha del acta',
            'nombre_representante'=>'nombre',
            'cargo_representante1'=>'cargo',
            'numero_identificacion_representante'=> 'número de identificación',
            'tipo_identificacion'=>'tipo de identificación',


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
