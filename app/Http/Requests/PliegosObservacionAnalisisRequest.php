<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PliegosObservacionAnalisisRequest extends FormRequest
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
            'calificacion_sugerida' => 'required|string|max:30|in:Solventado,No Solventado,Solventado Parcialmente',
            'conclusion' => 'required|string|max:8000',
            'monto_solventado' => 'sometimes|nullable|required_if:calificacion_sugerida,Solventado Parcialmente',
            // 'promocion' => 'sometimes|nullable|string|max:8000|required_if:calificacion_sugerida,Solventado Parcialmente,No Solventado',
            'monto_promocion' => 'sometimes|nullable|required_if:promocion,1,4',
            ];
    }

    public function attributes()
    {
        return [
           'analisis' => 'análisis',
           'calificacion_sugerida' => 'calificación sugerida de la atención',
           'conclusion'=>'conclusión',
           'monto_solventado'=>'monto solventado',
        //    'promocion'=>'promoción',
           'monto_promocion'=>'monto promoción'
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'monto_solventado.required_if' => 'El campo :attribute es obligatorio.',
            // 'promocion.required_if'=> 'El campo :attribute es obligatorio.',
            'monto_promocion.required_if' => 'El campo :attribute es obligatorio.',
        ];
    }
}
