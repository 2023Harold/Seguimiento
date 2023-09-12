<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecomendacionesRequest extends FormRequest
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
            'nombre_responsable' => 'required|string|max:150',
            'cargo_responsable' => 'required|string|max:150',
            'oficio_contestacion' => 'required|string|max:150',
            'analisis' => 'required|string|max:6000',
            'calificacion_atencion'=>'required|string|max:10',
            'conclusion' => 'required|string|max:6000',                      
        ];
    }

    public function attributes()
    {
        return [
            'nombre_responsable' => 'nombre del responsable de atender las recomendaciones por parte de la entidad fiscalizable',
            'oficio_contestacion' => 'oficio de contestación de la recomendación',
            'fecha_oficio_designacion' => 'fecha del oficio',
            'analisis' => 'análisis',
            'calificacion_atencion' => 'calificación de la atención',
            'conclusion' => 'conclusión',            
            
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',            
        ];
    }
}
