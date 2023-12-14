<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PRASSeguimientoRequest extends FormRequest
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
            'oficio_contestacion'=>'required|string|max:100',
            'fecha_acuse_contestacion'=>'required|date|max:10',
            'estatus_cumplimiento'=>'required|string|max:15|in:Atendido,No Atendido',
            'conlusion_pras'=>'required|string|max:8000'            
        ];
    }
    
    public function attributes()
    {
        return [
            'oficio_contestacion'=>'contestación OIC',
            'fecha_acuse_contestacion'=>'fecha del acuse de contestación',
            'estatus_cumplimiento'=>'estatus de cumplimiento',
            'conlusion_pras'=>'conclusión',     
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
        ];
    }
}
