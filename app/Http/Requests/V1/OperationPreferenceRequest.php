<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OperationPreferenceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'code.string' => 'Le code doit être une chaîne de caractères.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être numérique.',
            'price.min' => 'Le prix ne peut pas être négatif.',
        ];
    }
}
