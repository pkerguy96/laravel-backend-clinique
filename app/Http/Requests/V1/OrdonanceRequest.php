<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrdonanceRequest extends FormRequest
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
            'date' => 'required|date',
            'medicine' => 'required|array',
            'medicine.*.medicine_name' => 'required|string',
            'medicine.*.note' => 'nullable|string',
        ];
    }
    public function messages(): array
    { 
        return [
            'patient_id.required' => 'Le champ "patient" est requis.',
            'patient_id.integer' => 'Le champ "patient_id" doit être un entier.',
            'patient_id.exists' => 'Le "patient_id" spécifié n\'existe pas.',
            'date.required' => 'Le champ "date" est requis.',
            'date.date' => 'Le champ "date" doit être une date valide.',
            'medicine.*.medicine_name.required' => 'Le champ "medicine" est requis.',
            'medicine.*.medicine_name.string' => 'Le champ "medicine" doit être une chaîne de caractères.',
            'medicine.*.note.string' => 'Le champ "note" doit être une chaîne de caractères.',
        ];
    }
}
