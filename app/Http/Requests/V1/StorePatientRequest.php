<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        /* this needs to be true when authorized */
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
            'mutuelle' => ['required'],
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'phone_number' => $this->phoneNumber
        ]);
    }
}
