<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Product;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required_without:category_name', 'integer', 'exists:categories,id'],
            'category_name' => ['required_without:category_id', 'string', 'exists:categories,name'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'supplier_name' => ['nullable', 'string', 'exists:suppliers,name'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:kg,g,l,ml,pcs'],
            'expiration_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A termék neve kötelező.',
            'name.string' => 'A termék neve szöveg kell legyen.',
            'name.max' => 'A termék neve túl hosszú (max. 255 karakter).',

            'description.string' => 'A leírás szöveg kell legyen.',

            'category_id.required_without' => 'A kategória megadása kötelező.',
            'category_id.integer' => 'A kategória azonosítója csak szám lehet.',
            'category_id.exists' => 'A megadott kategória nem létezik.',

            'category_name.required_without' => 'A kategória megadása kötelező.',
            'category_name.string' => 'A kategória neve szöveg kell legyen.',
            'category_name.exists' => 'A megadott kategória nem létezik.',

            'supplier_id.integer' => 'A beszállító azonosítója csak szám lehet.',
            'supplier_id.exists' => 'A megadott beszállító nem létezik.',

            'supplier_name.string' => 'A beszállító neve szöveg kell legyen.',
            'supplier_name.exists' => 'A megadott beszállító nem létezik.',

            'quantity.required' => 'A mennyiség megadása kötelező.',
            'quantity.numeric' => 'A mennyiség csak szám lehet.',
            'quantity.min' => 'A mennyiség nem lehet negatív.',

            'unit.required' => 'Az egység megadása kötelező.',
            'unit.in' => 'Az egység csak ezek egyike lehet: kg, g, l, ml, pcs.',

            'expiration_date.required' => 'A lejárati dátum megadása kötelező.',
            'expiration_date.date' => 'A lejárati dátum formátuma érvénytelen.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Adatbeviteli hiba',
            'data' => $validator->errors(),
        ], 422));
    }


}
