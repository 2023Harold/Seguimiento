<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudesAclaracionContestacionRequest extends FormRequest
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
            'oficio_atencion' => 'required|string|max:150',
            'fecha_oficio_atencion' => 'required|date|max:10',                    
        ];
    }

    public function attributes()
    {
        return [           
            'oficio_atencion' => 'oficio de contestación de la solicitud de aclaración',
            'fecha_oficio_atencion' => 'fecha del oficio de contestación',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',            
        ];
    }
}