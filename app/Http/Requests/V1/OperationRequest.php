<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
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
            'patient_id' => 'required|integer|exists:patients,id',
            'note' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [

            'patient_id.required' => 'Le champ patient est requis.',
            'patient_id.integer' => 'Le champ patient doit être un entier.',
            'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',
            'note.string' => 'Le champ note doit être une chaîne de caractères.',
        ];
    }
}
