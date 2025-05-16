<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoliosRequest extends FormRequest
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
            'oficio_contestacion_general' => 'required|string|max:500',
            'fecha_oficio_contestacion' => 'required|date|max:100',
            'numero_oficio' => 'string|max:100',
            'nombre_remitente' => 'required|string|max:300',
            'cargo_remitente' => 'required|string|max:300',
            'folio' => 'required|string|max:100',
            'fecha_recepcion_oficialia' => 'required|date|max:10',
            'fecha_recepcion_us' => 'required|date|max:10',
            'solicitudes'=> 'required|string|in:Acciones,Recomendaciones,Ambas',
            'presentacionambs'=>'sometimes|nullable|required_if:solicitudes,Ambas|string|in:En tiempo,Extemporaneo',
            //'sol_extemp_rd'=>'sometimes|nullable|required_without:presentacion',
            'presentacion'=>'sometimes|nullable|required_if:presentacionambs,Extemporaneo',
            ];
    }

    public function attributes()
    {
        return [
            'oficio_contestacion_general' => 'oficio de contestación',
            'fecha_oficio_contestacion' => 'fecha oficio de contestación',
            'numero_oficio' => 'número del oficio',
            'nombre_remitente' => 'nombre del remitente',
            'cargo_remitente' => 'cargo del remitente',
            'folio' => 'folio',
            'fecha_recepcion_oficialia' => 'Fecha de recepción en Oficialia de Partes',
            'fecha_recepcion_us' => 'Fecha de recepción en la Unidad de Seguimiento',
            'presentacionambs'=>'presentación',
            'sol_extemp_rd'=> 'solicitudes en el oficio de contestación',
           ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'presentacion.required_if' => 'Se necesita seleccionar al menos una opción.',
        ];
    }
}
