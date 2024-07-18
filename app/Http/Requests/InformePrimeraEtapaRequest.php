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
            'numero_ordenauditoria' => 'required|string|max:100',
            'fecha_notificacion_oficio' => 'required|date_format:Y-m-d|max:10',
            'numero_oficio_entro' => 'required|string|max:100',
            'acta_reunion_resultados' => 'required|string|max:100',
            'fecha_notificación' => 'required|date_format:Y-m-d|max:10',
            'informe_seguimiento' => 'required|string|max:100',
            'fojas_utiles' => 'required|string|max:100',
            'clave_accion_pliego'=>'required|string|max:100',           

        ];
    }    
        public function attributes()
    {
        return [
            'numero_ordenauditoria' => 'número de la orden de la auditoría',
            'fecha_notificacion_oficio' => 'fecha de notificación del oficio con el cual se le entregó a la entidad fiscalizable el informe de auditoría',
            'numero_oficio_entro' => 'número de oficio por el cual se entregó el informe de auditoría',
            'acta_reunion_resultados' => 'acta de reunión de resultados y cierre de auditoría',
            'fecha_notificación' => 'fecha de notificación del oficio por el cual se remitieron las constancias que comprenden el informe de seguimiento',
            'informe_seguimiento'=>'informe de seguimiento ',
            'fojas_utiles'=>'fojas útiles',
            'clave_accion_pliego'=>'número de acta administrativa de comparecencia',
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
