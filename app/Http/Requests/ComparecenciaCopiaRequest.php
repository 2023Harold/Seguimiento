<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComparecenciaCopiaRequest extends FormRequest
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
            'nombre' => 'required|string|max:75',
            'domicilio_notificacion' => 'sometimes|nullable|string',
            'calle' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:100',
            'numero_domicilio' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:10',
            'colonia' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:100',
            'municipio' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:100',
            'entidad_federativa' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:100',
            'codigo_postal' => 'sometimes|nullable|required_without:domicilio_notificacion|string|max:5',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'nombre de la persona a la que se dirige la copia',
            'domicilio_notificacion' => '¿El domicilio de la persona a la que se dirige la copia es el mismo que el domicilio de notificación?',
            'calle' => 'calle',
            'numero_interior' => 'número',
            'colonia' => 'colonia',
            'municipio' => 'municipio',
            'entidad_federativa' => 'entiidad_federativa',
            'codigo_postal' => 'código postal',    
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
