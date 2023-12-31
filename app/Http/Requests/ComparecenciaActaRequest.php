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
            'oficio_designacion' => 'required|string|max:100',
            'fecha_designacion' => 'required|date_format:Y-m-d|max:10',
            'hora_comparecencia_termino' => 'required|string|max:15',
            'tipo_identificacion'=>'required|string|max:100',
            'tipo_identificacion1'=>'required|string|max:100',
            'tipo_identificacion2'=>'required|string|max:100',
            //'hora_comparecencia_termino' => 'hora de término de la comparecencia',
            'nombre_representante'=> 'required|string|max:100',
            'cargo_representante1'=> 'required|string|max:300',
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
            'oficio_designacion' => 'oficio de la designación',
            'fecha_designacion' => 'fecha de la designación',
            'nombre_representante'=>'nombre',
            'cargo_representante1'=>'cargo',
            'numero_identificacion_representante'=> 'número de identificación',
            'tipo_identificacion'=>'tipo de identificación',
            'tipo_identificacion1'=>'tipo de identificación',
            'tipo_identificacion2'=>'tipo de identificación',
            'nombre_testigo1'=>'nombre',
            'cargo_testigo1'=>'cargo',
            'numero_identificacion_testigo1'=> 'número de identificación',
            'nombre_testigo2'=>'nombre',
            'cargo_testigo2'=>'cargo',
            'numero_identificacion_testigo2'=> 'número de identificación',


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
