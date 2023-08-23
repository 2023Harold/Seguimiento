<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class segagenda_comparecencia extends FormRequest
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
            //
            'fecha' => 'required|date|max:10',
            'hora_inicio' => 'required|string|max:15',
            'hora_fin' => 'required|string|max:15',
        ];
    }
    public function attributes()
    {
        return [    
            'fecha' => 'fecha de la comparecencia',
            'hora_inicio' => 'Hora de inicio de la comparecencia',     
            'hora_fin' => 'Hora fin de la comparecencia',
    ];
    }

    public function messages()
    {
        return [
            'fecha'=> 'El campo :attribute es obligatorio',
            'hora_inicio' =>'El campo :attribute es obligatorio',     
            'hora_fin' => 'El campo :attribute es obligatorio',
        ];
    }
}
