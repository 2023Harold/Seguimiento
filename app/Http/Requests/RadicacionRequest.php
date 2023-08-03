<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RadicacionRequest extends FormRequest
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
            'numero_acuerdo' => 'required|string|max:30',
            'oficio_acuerdo' => 'required|string|max:100',
            'fecha_oficio_acuerdo' => 'required|date|max:10',
            'oficio_designacion' => 'required|string|max:100',
            'fecha_oficio_designacion' => 'required|date|max:10',
        ];
    }

    public function attributes()
    {
        return [
            'numero_acuerdo' => 'número de acuerdo',
            'oficio_acuerdo' => 'acuerdo de radicación',
            'fecha_oficio_acuerdo' => 'fecha del acuerdo',
            'oficio_designacion' => 'oficio de designación',
            'fecha_oficio_designacion' => 'fecha del oficio',
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
