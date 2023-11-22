<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudesAclaracionAnexoRequest extends FormRequest
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
            'archivo' => 'required|string|max:150', 
            'nombre_archivo' => 'required|string|max:400',                          
            ];
    }

    public function attributes()
    {
        return [                    
           'archivo' => 'archivo',
           'nombre_archivo' => 'nombre del archivo',        
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',                    
        ];
    } 
}
