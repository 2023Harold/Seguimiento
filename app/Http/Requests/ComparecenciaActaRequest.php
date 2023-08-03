<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaActaRequest extends FormRequest
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
            'oficio_acta' => 'required|string|max:100',
            'numero_acta' => 'required|string|max:50',
            'fecha_acta' => 'required|date_format:Y-m-d|max:10'
        ];
    }

    public function attributes()
    {
        return [
            'oficio_acta' => 'acta de comparecencia',
            'numero_acta' => 'número de acta',
            'fecha_acta' => 'fecha del acta',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya se encuentra registrado.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
        ];
    }
}
