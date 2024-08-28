<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TipologiaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $message;

    private $accion;
    private $acto;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($accion,$acto)
    {
        $this->accion = $accion;
        $this->acto = $acto;
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
        if ($this->accion < 4) {
            
            if($this->acto==1||$this->acto==1||$this->acto==1)
            $this->message = 'El campo hora aproximada de termino debe ser posterior a la hora de inicio.';
           
            return false;
        }
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
