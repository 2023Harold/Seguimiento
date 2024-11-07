<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PliegosObservacionContestacionRequest extends FormRequest
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
            'fecha_oficio_contestacion'=>'sometimes|nullable|date|max:10',
            'numero_oficio'=>'required|string|max:250',
            'nombre_remitente'=>'required|string|max:500',
            'cargo_remitente'=>'required|string|max:500',
            'fecha_recepcion_oficialia'=>'required|date|max:10',
            'folio_correspondencia'=>'required|integer|max:9999999999',
            'fecha_recepcion_seguimiento'=>'required|date|max:10'
        ];
    }

    public function attributes()
    {
        return [
            'oficio_contestacion'=>'oficio de contestación de la recomendación',
            'fecha_oficio_contestacion'=>'fecha del oficio de contestación',
            'numero_oficio'=>'número del oficio',
            'nombre_remitente'=>'remitente',
            'cargo_remitente'=>'cargo del remitente',
            'fecha_recepcion_oficialia'=>'fecha de recepción en oficialía',
            'folio_correspondencia'=>'folio de correspondencia',
            'fecha_recepcion_seguimiento'=>'fecha de recepción en la unidad de seguimiento',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }
}
