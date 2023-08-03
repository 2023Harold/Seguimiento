<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignacionLiderAnalistaRequest extends FormRequest
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
            'lider_asignado_id' => 'sometimes|nullable|required_if:accion,reasignarlider,asignar|integer|max:999999999999',           
            'cargo_lider' => 'sometimes|nullable|required_if:accion,reasignarlider,asignar|string|max:200',
            'analista_asignado_id' => 'sometimes|nullable|required_if:accion,reasignaranalista,asignar|integer|max:999999999999',           
            'cargo_analista' => 'sometimes|nullable|required_if:accion,reasignaranalista,asignar|string|max:200',            
        ];
    }

    public function attributes()
    {
        return [
            'lider_asignado_id' => 'nombre del lider de proyecto',           
            'cargo_lider' => 'cargo del lider de proyecto',
            'analista_asignado_id' => 'nombre del analista',           
            'cargo_analista' => 'cargo del analista', 
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
