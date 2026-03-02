<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        $supplierId = $this->route('supplier')?->id;

        return [
            'name' => ['required', 'string', 'max:30'],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('suppliers', 'email')->ignore($supplierId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A beszállító neve kötelező.',
            'name.string' => 'A beszállító neve szöveg kell legyen.',
            'name.max' => 'A beszállító neve túl hosszú (max. 30 karakter).',

            'email.required' => 'Az email cím megadása kötelező.',
            'email.email' => 'Az email cím formátuma érvénytelen.',
            'email.max' => 'Az email cím túl hosszú (max. 50 karakter).',
            'email.unique' => 'Ez az email cím már foglalt.',

            'phone.string' => 'A telefonszám szöveg kell legyen.',
            'phone.max' => 'A telefonszám túl hosszú (max. 30 karakter).',

            'address.string' => 'A cím szöveg kell legyen.',
            'address.max' => 'A cím túl hosszú (max. 100 karakter).',
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
