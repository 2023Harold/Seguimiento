<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PRASTurnosRequest extends FormRequest
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
            
            'nombre_titular_oic'=>'required|string|max:100',
            'oficio_remision'=>'required|string',
            'fecha_acuse_oficio'=>'required|date|nullable',
            'numero_oficio'=>'required|string',
            'fecha_proxima_seguimiento'=>'required|date|nullable',


        ];
        
    }
    public function attributes()
    {
        return [
            'nombre_titular_oic' => 'nombre del titular',
            'oficio_remision' => 'oficio de turno',
            'fecha_acuse_oficio' => 'fecha del oficio turno',
            'numero_oficio' => 'número de oficio',
            'fecha_proxima_seguimiento' => 'fecha próxima de seguimiento',
            
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
