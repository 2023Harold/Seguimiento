<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutorizarFlujoAutorizacionRequest extends FormRequest
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
            'estatus' => 'required',
            'motivo_rechazo' => 'bail|sometimes|nullable|required_if:estatus,Rechazado|string|max:1000',
            'certificate_file' => 'required',
            'privkey_file' => 'required',
            'password' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'estatus' => '',
            'motivo_rechazo' => 'motivo del rechazo',
            'certificate_file' => 'certificado digital',
            'privkey_file' => 'llave pública',
            'password' => 'contraseña',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'motivo_rechazo.required_if' => 'El campo :attribute es obligatorio',
        ];
    }
}
