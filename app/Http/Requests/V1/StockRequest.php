<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ];
    }
    public function messages()
    {
        return [
            'category.required' => 'le nom de la catégorie est requis.',
            'brand.required' => 'la marque est requis.',
            'product_name.required' => 'le nom du produit est requise.',
            'quantity.required' => 'la quantité est requise.',
            'quantity.min' => 'La quantité doit être une valeur non négative.',
        ];
    }
}
