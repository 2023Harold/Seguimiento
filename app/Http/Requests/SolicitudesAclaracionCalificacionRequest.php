<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudesAclaracionCalificacionRequest extends FormRequest
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
            'cumple' => 'required|string|max:30|in:Atendida,No Atendida,Parcialmente Atendida',                             
            'monto_solventado' => 'sometimes|nullable|required_if:cumple,Parcialmente Atendida'                             
            ];
    }

    public function attributes()
    {
        return [                    
           'cumple' => 'calificación de la atención',
           'monto_solventado'=>'monto solventado'            
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',            
            'monto_solventado.required_if' => 'El campo :attribute es obligatorio.',            
        ];
    }    
}
