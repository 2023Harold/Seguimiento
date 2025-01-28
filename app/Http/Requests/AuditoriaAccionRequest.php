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
            'acto_fiscalizacion_id' => 'required|integer|max:999999999999',
            'numero' => 'required|string|max:100',
            'cedula' => 'sometimes|nullable|string|max:100',
            'accion' => 'required|string|max:50000',    
            'antecedentes_accion' => 'required|string|max:50000',    
            'normativa_infringida' => 'required|string|max:3000',      
            'fecha_termino_recomendacion' => 'sometimes|nullable|required_if:segtipo_accion_id,2|string|max:250',
            'plazo_recomendacion' => 'sometimes|nullable|required_if:segtipo_accion_id,2|string|max:250',
            // 'tipologia_id' => 'integer|max:999999999',
            'monto_aclarar'=> 'sometimes|nullable|required_if:segtipo_accion_id,1,3,4',
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
            'antecedentes_accion' => 'antecedentes de la acción',
            'normativa_infringida' => 'normativa infringida',
            'monto_aclarar' => 'monto por aclarar',
            'evidencia_recomendacion' => 'evidencia documental que acredite la atención de la recomendación',
            'tipo_recomendacion' => 'tipo de recomendación',
            'tramo_control_recomendacion' => 'tramo de control',
            'fecha_termino_recomendacion' => 'fecha de término',
            'plazo_recomendacion' => 'plazo convenido',
            // 'tipologia_id' => 'tipología',
            'monto_aclarar'=> 'monto por aclarar',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya se encuentra registrado.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
            'required_with' => 'El campo :attribute es obligatorio.',
        ];
    }
}
