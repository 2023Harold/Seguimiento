<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcuerdoConclusionRequest extends FormRequest
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
            'nombre_titular'=>'required|string|max:100',
            'cargo_titular'=>'required|string|max:100',
            'domicilio'=>'required|string|max:150',
            'numero_acuerdo_conclusion' => 'required|string|max:100',
            'fecha_acuerdo_conclusion' => 'required|date_format:Y-m-d|max:10',
            'acuerdo_conclusion' => 'required|string|max:100',
        ];
    }    
        public function attributes()
    {
        return [
           'nombre_titular'=>'el nombre del titular a quien se dirige',
           'cargo_titular'=>'el cargo del titular a quien se dirige',
           'domicilio'=>'el domicilio',
           'numero_acuerdo_conclusion' => 'número del informe de auditoría',
           'fecha_acuerdo_conclusion' => 'fecha del acuerdo de conclusion',
           'informe' => 'acuerdo de conclusión',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            // 'unique' => 'El :attribute ya se encuentra registrado.',
            // 'required_if' => 'El campo :attribute es obligatorio.',
            // 'required_without' => 'El campo :attribute es obligatorio.',

        ];
    }
}
