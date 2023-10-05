<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class pliegosObservacionDocumentoRequest extends FormRequest
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
            'nombre_documento'=>'required|string|max:200'
        ];
    }

    public function attributes()
    {
        return [
            'nombre_documento' => 'nombre del documento',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }
}
