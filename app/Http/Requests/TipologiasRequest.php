<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipologiasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
        'tipologia_id'=> 'required|integer|max:999999999999',
        ];
    }
    public function attributes()
    {
        return [
        'tipologia_id'=> 'tipología',
        ];
    }
    public function messages()
    {
        return [
        'required' => 'El campo :attribute es obligatorio.',
        ];
    }

}
