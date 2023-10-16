<?php

namespace App\Http\Requests;

use App\Rules\ListaDocumentosRule;
use Illuminate\Foundation\Http\FormRequest;

class PliegosObservacionCalificacionRequest extends FormRequest
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
            'conclusion' => 'required|string|max:8000',
            'calificacion_atencion' => 'required|string|max:30|in:Solventado,No Solventado,Solventado Parcialmente',
            'monto_solventado' => 'sometimes|nullable|required_if:calificacion_atencion,Solventado Parcialmente'   
            ];
    }

    public function attributes()
    {
        return [
           'conclusion' => 'conclusión',
           'calificacion_atencion' => 'calificación de la atención',
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
