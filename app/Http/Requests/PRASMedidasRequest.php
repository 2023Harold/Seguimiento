<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PRASMedidasRequest extends FormRequest
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
            'oficio_medida_apremio'=>'required|string|max:100',
            'fecha_acuse_medida_apremio'=>'required|date|max:10'       
        ];
    }

    public function attributes()
    {
        return [
            'oficio_medida_apremio'=>'medida de apremio',
            'fecha_acuse_medida_apremio'=>'fecha del acuse de la medida de apremio'
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
