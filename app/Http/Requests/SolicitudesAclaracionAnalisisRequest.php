<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudesAclaracionAnalisisRequest extends FormRequest
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
            'analisis' => 'required|string|max:8000',
            'conclusion' => 'required|string|max:8000',
            'calificacion_sugerida' => 'required|string|max:30|in:Solventada,No Solventada,Solventada Parcialmente'
            ];
    }

    public function attributes()
    {
        return [
           'analisis' => 'análisis',
           'conclusion'=> 'conclusión',
           'calificacion_sugerida' => 'calificación sugerida de la atención'
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }
}
