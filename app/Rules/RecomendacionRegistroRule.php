<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RecomendacionRegistroRule implements Rule
{
    private $message;
    private $tipoaccion;
    private $actofiscalizacion;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tipoaccion,$actofiscalizacion)
    {
        $this->tipoaccion = $tipoaccion;
        $this->actofiscalizacion = $actofiscalizacion;
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
        $tipoaccion=intval($this->tipoaccion);
        $actofiscalizacion=intval($this->actofiscalizacion);
        $namecampo ='';
        switch ($attribute) {
            case 'evidencia_recomendacion':
                $namecampo ='evidencia documental que acredite la atención de la recomendación';
                break;
            case 'tipo_recomendacion':
                $namecampo ='tipo de recomendación';
                break;
            case 'tramo_control_recomendacion':
                $namecampo ='tramo de control';
                break;      
        }

        if(empty($value)){
            if($tipoaccion==2){
                if($actofiscalizacion==3||$actofiscalizacion==4){

                    $this->message = 'El campo '.$namecampo.' es obligatorio. ';
                    return false;
                }
            }
        }else{
            $tamano=strlen($value);
            if($tamano>250){
                $this->message = 'El campo '.$namecampo.' no debe exceder de 250 caracteres.';
                    return false;
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
