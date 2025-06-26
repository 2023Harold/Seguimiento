<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcuerdosValoracionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'numero_expediente'=>'required|string|max:100',
            'tipo_doc'=>'required|string|max:100',
            'numero_oficio_ent'=>'required|string|max:500',
            'fecha_oficio_ent'=>'required|date_format:Y-m-d|max:10',
            'nombre_firmante'=>'required|string|max:500',
            'cargo_firmante'=>'required|string|max:500',
			'administracion_firmante'=>'required|string|max:500',
            'nombre_informe_au' => 'required|string|max:500',
            'cargo_informe_au' => 'required|string|max:500',
            'administracion_informe_au' => 'required|string|max:500',
        ];
    }    
        public function attributes()
    {
        return [
           'numero_expediente'=>'numero de expediente',
           'tipo_doc'=>'tipo de documento (oficio u escrito)',
           'numero_oficio_ent'=>'numero de oficio presentado por la entidad',
		   'fecha_oficio_ent'=>'fecha del oficio presentado por la entidad',
           'nombre_firmante'=>'nombre del firmante',
           'cargo_firmante' => 'cargo del firmante',
           'administracion_firmante' => 'administracion del firmante',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            // 'unique' => 'El :attribute ya se encuentra registrado.',
            // 'required_if' => 'El campo :attribute es obligatorio.',
            // 'required_without' => 'El campo :attribute es obligatorio.',

        ];
    }
}
