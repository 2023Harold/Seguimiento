<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformePrimeraEtapaRequest extends FormRequest
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
            'fecha_informe' => 'required|date_format:Y-m-d|max:10',
			'numero_informe'=> 'required|string|max:100',			
			'nombre_titular_informe'=> 'required|string|max:500',
			'cargo_titular_informe'=> 'required|string|max:500',
			'domicilio_informe'=> 'required|string|max:500',
			'numero_fojas'=> 'required|integer|max:999999999',
			'informe'=> 'required|string|max:100',
			'acuse_envio'=> 'required|string|max:100',
			'fecha_acuse_envio'=> 'required|date|max:10',
			'acuse_notificacion'=> 'required|string|max:100',
			'fecha_notificacion'=> 'required|date|max:10',
        ];
    }    
        public function attributes()
    {
        return [
			'fecha_informe' => 'fecha del oficio de notificación del informe de seguimiento',
			'numero_informe'=> 'Número de oficio de notificación',
			'nombre_titular_informe'=> 'nombre del titular a quien se dirige',
			'cargo_titular_informe'=> 'cargo del titular a quien se dirige',
			'domicilio_informe'=> 'domicilio',
			'numero_fojas'=> 'número de fojas',
			'informe'=> 'informe de seguimiento',
			'acuse_envio'=> 'acuse envío a notificar',
			'fecha_acuse_envio'=> 'fecha del envío a notificar',
			'acuse_notificacion'=> 'acuse de notificación',
			'fecha_notificacion'=> 'fecha de notificación',
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
