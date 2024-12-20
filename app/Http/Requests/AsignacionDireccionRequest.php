<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignacionDireccionRequest extends FormRequest
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
            'direccion_asignada_id' => 'required|integer|max:999999999999',
            'nombre' => 'required|string|max:100',
            'cargo' => 'required|string|max:200',         
            'staff_asignada_id' => 'required|integer|max:999999999999',
   
        ];
    }

    public function attributes()
    {
        return [
            'direccion_asignada_id' => 'departamento',
            'nombre' => 'nombre',
            'cargo' => 'cargo',
            'staff_asignada_id'=> 'staff juridico',
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
