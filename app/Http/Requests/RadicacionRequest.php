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
            'numero_expediente'=>'required|string|max:150',
            'numero_acuerdo' => 'required|string|max:30',
            'oficio_acuerdo' => 'required|string|max:100',
            'fecha_oficio_acuerdo' => 'required|date|max:10',
            
            // 'oficio_designacion' => 'required|string|max:100',
            // 'fecha_oficio_designacion' => 'required|date|max:10|after_or_equal:fecha_oficio_acuerdo',
            'nombre_titular' => 'required|string|max:120',
            'cargo_titular' => 'required|string|max:120',
            'fecha_comparecencia' => 'required|date|max:10',
            //'fecha_comparecencia' => 'required|date|max:10'|after:fecha_oficio_acuerdo'
            'hora_comparecencia_inicio' => 'required|string|max:15',
            'aplicacion_periodo'=>  'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Legalidad,Cumplimiento Financiero,Inversión Física|string|max:2',
            'fecha_inicio_aclaracion' => 'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Cumplimiento Financiero,Inversión Física|date|max:10',
            'fecha_termino_aclaracion' => 'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Cumplimiento Financiero,Inversión Física|date|max:10',
            'fecha_inicio_proceso' => 'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Desempeño|date|max:10',
            'fecha_termino_proceso' => 'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Desempeño|date|max:10',
            'num_memo_recepcion_expediente'=>'required|string|max:200',
            'fecha_expediente_turnado'=>'required|date|max:10',
            'fecha_oficio_informe'=>'required|date|max:10',
            'fecha_notificacion'=>'required|date|max:10',
            'plazo_maximo'=>'sometimes|nullable|required_if:acto_fiscalizacion_auditoria,Desempeño|integer|max:120',
            'acta_cierre_auditoria'=>'required|string|max:150',          
            'fecha_acta'=>'required|string|max:150',
        ];
    }

    public function attributes()
    {
        return [
            'numero_expediente'=>'número de expediente US',
            'numero_acuerdo' => 'número de oficio de notificación de acuerdos',
            'oficio_acuerdo' => 'acuerdo de radicación',
            'fecha_oficio_acuerdo' => 'fecha del acuerdo',
            // 'oficio_designacion' => 'oficio de designación',
            // 'fecha_oficio_designacion' => 'fecha del oficio',
            'nombre_titular' => 'nombre del titular  a quien se dirige la comparecencia',
            'cargo_titular' => 'cargo del titular a quien se dirige la comparecencia',
            'fecha_comparecencia' => 'fecha de la comparecencia',
            'hora_comparecencia_inicio' => 'hora de inicio de la comparecencia',
            'aplicacion_periodo'=>'¿El periodo de la etapa de aclaración es de 30 días hábiles?',
            'fecha_inicio_aclaracion'=>'inicio de la etapa de aclaración',
            'fecha_termino_aclaracion'=>'término de la etapa de aclaración',
            'fecha_inicio_proceso' => 'inicio del proceso de atención',
            'fecha_termino_proceso' => 'término del proceso de atención',
            'num_memo_recepcion_expediente'=>'número del memorándum de recepción del expediente',
            'fecha_expediente_turnado'=>'fecha de recepción del expediente turnado',
            'fecha_oficio_informe'=>'fecha oficio informe',
            'fecha_notificacion'=>'fecha radicación',
            'plazo_maximo'=>'plazo máximo', 
            'acta_cierre_auditoria'=> 'acta de reunión de resultados finales y cierre de auditoría', 
            'fecha_acta'  => 'fecha de acta', 

        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El :attribute ya se encuentra registrado.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'required_without' => 'El campo :attribute es obligatorio.',
            // 'fecha_oficio_designacion.after_or_equal'=> 'El campo :attribute debe ser posterior o igual a la fecha del acuerdo',
            // 'fecha_comparecencia.after'=> 'El campo :attribute debe ser posterior a la fecha del acuerdo'
        ];
    }
}
