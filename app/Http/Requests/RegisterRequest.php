<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
    public function rules(): array {
        return [
            "name" => [ "required", "between:3,16", "unique:users,name", "doesnt_start_with:_"],
            "email" => [ "required", "email", "unique:users,email" ],
            "password" => [ "required", "confirmed",
            Password::min( 6 )
                      ->mixedCase()
                      ->numbers()
                      ->symbols()],
            "full_name" => [ "required", "max:70" ],
            "city" => [  "max:50" ],
            "address" => [  "max:100" ],
            "phone" => [  "max:25" ]
        ];
    }

    public function messages() {

        return [
            "name.required" => "A név megadása kötelező.",
            "name.between" => "A név hossza 3 és 16 karakter között kell legyen.",
            "name.unique" => "Ez a felhasználónév már foglalt.",
            "name.doesnt_start_with" => "A név nem kezdődhet alulvonással.",
            "email.required" => "Az email cím megadása kötelező.",
            "email.email" => "Az email cím formátuma érvénytelen.",
            "email.unique" => "Ez az email cím már foglalt.",
            "password.required" => "A jelszó megadása kötelező.",
            "password.confirmed" => "A jelszavaknak egyezniük kell.",
            "password.min" => "A jelszónak legalább 6 karakter hosszúnak kell lennie.",
            "password.mixed" => "A jelszónak tartalmaznia kell kis- és nagybetűt is.",
            "password.mixedCase" => "A jelszónak tartalmaznia kell kis- és nagybetűt is.",
            "password.numbers" => "A jelszónak tartalmaznia kell számot is.",
            "password.symbols" => "A jelszónak tartalmaznia kell speciális karaktert is.",


            "full_name.max" => "A teljes név legfeljebb 70 karakter lehet.",
            "full_name.required" => "A teljes név megadása kötelező.",
            "city.max" => "A város neve legfeljebb 50 karakter lehet.",
            "address.max" => "A cím legfeljebb 100 karakter lehet.",
            "phone.max" => "A telefonszám legfeljebb 25 karakter lehet."
        ];
    }

    public function failedValidation( Validator $validator ) {

        throw new HttpResponseException( response()->json([

            "success" => false,
            "message" => "Adatbeviteli hiba",
            "data" => $validator->errors()
        ]));
    }
}

// same:password, confirmed
