<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecomendacionesAtencionAcusesRequest extends FormRequest
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
            'oficio_comprobante'=>'required|string|max:100',
            'fecha_comprobante'=>'required|date|max:10',
            'oficio_acuse'=>'required|string|max:100',
            'fecha_acuse'=>'required|date|max:10',            
        ];
        
    }
    public function attributes()
    {
        return [
            'oficio_comprobante'=>'comprobante de recepción depto. de notificaciones',
            'fecha_recepcion'=>'fecha del comprobante',
            'oficio_acuse'=>'acuse del turno del PRAS',
            'fecha_acuse'=>'fecha del acuse', 
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required' => 'El :attribute es obligatorio',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
        ];
    }
}
