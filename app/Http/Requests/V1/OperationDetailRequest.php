<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OperationDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tooth_id' => 'required',
            'operation_type' => 'required',
            'price' => 'required|numeric|min:0.01',
        ];
    }
    public function messages()
    {
        return [
            'tooth_id.required' => 'Le champ ID dent est requis.',

            'operation_type.required' => 'Le champ type d\'opération est requis.',

            'price.required' => 'Le champ prix est requis.',
            'price.numeric' => 'Le champ prix doit être un nombre.',
            'price.min' => 'Le champ prix doit être d\'au moins :min.',
        ];
    }
}
