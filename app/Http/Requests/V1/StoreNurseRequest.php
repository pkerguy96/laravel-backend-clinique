<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNurseRequest extends FormRequest
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
            'nom' => ['required'],
            'prenom' => ['required'],
            'cin' => ['required'],
            'date' => [
                'required',
                'date',
                'before_or_equal:today', // Ensures it's not in the future

            ],
            'address' => ['required'],
            'sex' => ['required', Rule::in(['male', 'female'])],
            'phone_number' => ['required', 'numeric'],

        ];
    }
    public function messages()
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'cin.required' => 'Le CIN est requis.',
            'date.required' => 'La date de naissance est requise.',
            'date.date' => 'La date de naissance doit être une date valide.',
            'date.before_or_equal' => 'La date de naissance ne peut pas être dans le futur.',
            'address.required' => 'L\'adresse est requise.',
            'sex.required' => 'Le sexe est requis.',
            'sex.in' => 'Le sexe doit être soit "homme" soit "femme".',
            'phone_number.required' => 'Le numéro de téléphone est requis.',
            'phone_number.numeric' => 'Le numéro de téléphone doit être numérique.',

        ];
    }
}
