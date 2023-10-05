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
            'fecha_oficio_contestacion'=>'required|date|max:10',
        ];
    }

    public function attributes()
    {
        return [
            'oficio_contestacion'=>'oficio de contestación de pliegos de observación',
            'fecha_oficio_contestacion'=>'fecha del oficio de contestación',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }
}
