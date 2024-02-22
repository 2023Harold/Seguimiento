<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditoriaRequest extends FormRequest
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
            'numero_auditoria' => 'required|string|max:100',
            'entidad_n1' => 'sometimes|nullable|required_without:entidad_descripcion|integer|max:999999999999',
            'entidad_n2' => 'sometimes|nullable|required_without_all:entidad_fiscalizable_id,entidad_descripcion|integer|max:999999999999',
            'entidad_n3' => 'sometimes|nullable|required_without_all:entidad_fiscalizable_id,entidad_descripcion|integer|max:999999999999',
            'entidad_descripcion'=>'sometimes|nullable|required_without:entidad_fiscalizable_id|string|max:2000',
            'periodo_revision' => 'required|string|max:100',
            'tipo_auditoria_id' => 'required|integer|max:9999999999',
            'lider_proyecto_id' => 'required|integer|max:9999999999',
        ];
    }

    public function attributes()
    {
        return [
            'numero_auditoria' => 'número de auditoría',
            'entidad_n1' => 'entidad fiscalizable',
            'entidad_n2' => 'entidad fiscalizable',
            'entidad_n3' => 'entidad fiscalizable',
            'periodo_revision' => 'periodo de la revisión',
            'tipo_auditoria_id' => 'acto de fiscalización',

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
