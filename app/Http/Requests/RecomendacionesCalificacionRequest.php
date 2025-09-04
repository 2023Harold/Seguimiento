<?php

namespace App\Http\Requests;

use App\Rules\ListaDocumentosRule;
use Illuminate\Foundation\Http\FormRequest;

class RecomendacionesCalificacionRequest extends FormRequest
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
            'conclusion' => 'required|string', 
            'calificacion_atencion' => 'required|string|max:30|in:Atendida,No Atendida,Parcialmente Atendida'                             
            ];
    }

    public function attributes()
    {
        return [
           'conclusion' => 'conclusión',            
           'calificacion_atencion' => 'calificación de la atención',            
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',            
        ];
    }    
}
