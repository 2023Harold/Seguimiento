<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DatosDetalle implements Rule
{
    private $message;

    private $numeroregistros;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($numeroregistros)
    {
        $this->numeroregistros = $numeroregistros;
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
        if ($value == 'Si' && $this->numeroregistros == 0) {
            if ($attribute == 'anexos') {
                $this->message = 'No se ha capturado información en el apartado de anexos. ';
            }
            if ($attribute == 'copias_conocimiento') {
                $this->message = 'No se ha capturado información en el apartado de copias de conocimiento. ';
            }

            return false;
        }

        if ($value == 'No' && $this->numeroregistros > 0) {
            if ($attribute == 'anexos') {
                $this->message = 'Actualmente se encuentra información en el apartado de anexos. ';
            }
            if ($attribute == 'copias_conocimiento') {
                $this->message = 'Actualmente se encuentra información en el apartado de copias de conocimiento';
            }

            return false;
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
