<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PayementRequest extends FormRequest
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
            'amount_paid' => 'required_if:is_paid,true|numeric|min:0.01',
            'is_paid' => 'required|boolean',
        ];
    }
    public function messages()
    {
        return [
            // ...

            // Payment
            'amount_paid.required_if' => 'Le champ montant payé est requis lorsque le paiement est effectué.',
            'amount_paid.numeric' => 'Le champ montant payé doit être un nombre.',
            'amount_paid.min' => 'Le champ montant payé doit être d\'au moins :min.',
            'is_paid.required' => 'Le champ paiement effectué est requis.',
            'is_paid.boolean' => 'Le champ paiement effectué doit être un booléen.',
        ];
    }
}
