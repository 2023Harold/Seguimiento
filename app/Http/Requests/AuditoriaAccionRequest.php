<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditoriaAccionRequest extends FormRequest
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
            'consecutivo' => 'required|string|max:10',
            'segtipo_accion_id' => 'required|integer|max:999999999999',
            'numero' => 'required|string|max:100',
            'cedula' => 'sometimes|nullable|string|max:100',
            'accion' => 'required|string|max:50000',
            'monto_aclarar' => 'sometimes|nullable|required_if:segtipo_accion_id,1,3,4|string|max:20|no_js_validation',
        ];        
    }

    public function attributes()
    {
        return [
            'consecutivo' => 'número consecutivo',
            'tipo_accion_id' => 'tipo de acción',
            'numero' => 'número de acción',
            'cedula' => 'cédula de acción',
            'accion' => 'acción',
            'monto_aclarar' => 'monto por aclarar',
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
