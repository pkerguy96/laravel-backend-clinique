<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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

            'patient_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'date' => 'required',
            'note' => 'nullable|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'patient_id.required' => 'Le nom de patient est requis.',
            'title.required' => 'Le titre est requis.',
            'date.required' => 'La date de naissance est requise.',
            'date.date' => 'La date de naissance doit être une date valide.',

        ];
    }
}
