<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['prohibited'],

            'full_name' => ['sometimes', 'string', 'max:70'],
            'city' => ['sometimes', 'string', 'max:50'],
            'address' => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:25'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.prohibited' => 'A felhasználónév nem módosítható.',

            'full_name.string' => 'A teljes név szöveg kell legyen.',
            'full_name.max' => 'A teljes név legfeljebb 70 karakter lehet.',
            'city.string' => 'A város neve szöveg kell legyen.',
            'city.max' => 'A város neve legfeljebb 50 karakter lehet.',
            'address.string' => 'A cím szöveg kell legyen.',
            'address.max' => 'A cím legfeljebb 100 karakter lehet.',
            'phone.string' => 'A telefonszám szöveg kell legyen.',
            'phone.max' => 'A telefonszám legfeljebb 25 karakter lehet.',
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
