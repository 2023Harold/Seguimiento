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
            'oficio_acreditacion' => 'required|string|max:100',
            'hora_comparecencia_termino' => 'required|string|max:15',
            //'hora_comparecencia_termino' => 'hora de término de la comparecencia',
            'nombre_representante'=> 'required|string|max:100',
            'cargo_representante'=> 'required|string|max:300',
            'numero_identificacion_representante'=> 'required|string|max:100',
            'nombre_testigo1'=> 'required|string|max:100',
            'cargo_testigo1'=> 'required|string|max:300',
            'numero_identificacion_testigo1'=> 'required|string|max:100',
            'nombre_testigo2'=> 'required|string|max:100',
            'cargo_testigo2'=> 'required|string|max:300',
            'numero_identificacion_testigo2'=> 'required|string|max:100',


        ];
    }

    public function attributes()
    {
        return [
            'oficio_acta' => 'acta de comparecencia',
            'numero_acta' => 'número de acta',
            'fecha_acta' => 'fecha del acta',
            'nombre_representante'=>'nombre del representante',
            'cargo_representante'=>'cargo del representante',
            'numero_identificacion_representante'=> 'numero de identificacion del representante',
            'nombre_testigo1'=>'nombre del testigo 1',
            'cargo_testigo1'=>'cargo del testigo 1',
            'numero_identificacion_testigo1'=> 'numero de identificacion del testigo 1',
            'nombre_testigo2'=>'nombre del testigo 2',
            'cargo_testigo2'=>'cargo del testigo 2',
            'numero_identificacion_testigo1'=> 'numero de identificacion del testigo 2',


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
