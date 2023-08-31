<?php

namespace App\Rules;

use App\Models\ComparecenciaAgenda;
use Illuminate\Contracts\Validation\Rule;

class TiempoRule implements Rule
{
    private $message;

    private $horainicio;
    private $fecha;
    private $sala;
    private $idagenda;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($horainicio,$fecha,$sala,$idagenda)
    {
        $this->horainicio = $horainicio;
        $this->fecha = $fecha;
        $this->sala = $sala;
        $this->idagenda = $idagenda;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $horafin=intval(str_replace(":", "", $value));
        $horainicio=intval(str_replace(":", "", $this->horainicio));
        
        if ($horainicio > $horafin) {           
            $this->message = 'El campo hora aproximada de termino debe ser posterior a la hora de inicio.';
           
            return false;
        }
        if($this->idagenda=='N/A'){
            $reunionesagendadas=ComparecenciaAgenda::where('sala',intval(str_replace("s", "", $this->sala)))->where('fecha',$this->fecha)->get();
        }else{
            $reunionesagendadas=ComparecenciaAgenda::whereNotIn('id',[$this->idagenda])->where('sala',intval(str_replace("s", "", $this->sala)))->where('fecha',$this->fecha)->get();
        }
        
        
        foreach ($reunionesagendadas as $reunion) {
            $inicioagendado=intval(str_replace(":", "", $reunion->hora_inicio));
            $finagendado=intval(str_replace(":", "", $reunion->hora_fin));
            
            $horainicioagendar=$horainicio;            
            $horafinagendar=$horafin;
            
            
            if($horainicioagendar <= $inicioagendado){
                if ($horafinagendar >= $inicioagendado) {
                    $this->message = 'El horario no se encuentra disponible.';   

                    return false;
                } else {
                    
                    return true;
                }
               
            }elseif($horainicioagendar >= $inicioagendado){ 
                if($horainicioagendar <= $finagendado){
                    $this->message = 'El horario no se encuentra disponible';   

                    return false;  
                }                             
            }           
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
