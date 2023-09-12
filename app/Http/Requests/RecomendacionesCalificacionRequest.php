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
            'analisis' => 'required|string|max:6000',
            'conclusion' => 'required|string|max:6000', 
            'calificacion_atencion' => 'required|string|max:15|in:Atendida,No Atendida'                             
            ];
    }

    public function attributes()
    {
        return [
           'analisis' => 'análisis',
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
