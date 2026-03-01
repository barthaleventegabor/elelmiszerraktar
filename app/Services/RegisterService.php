<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ResponseTrait;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class RegisterService {

    use ResponseTrait;

    public function __construct(){
    }

    public function create( array $data ) {

        $plainPassword = $data[ "password" ];

        $user = new User();
        $user->name = $data[ "name" ];
        $user->email = $data[ "email" ];
        $user->role = "user";
        $user->password = Hash::make( $plainPassword );

        $user->save();

        Mail::to( $user->email )->send( new WelcomeMail( $user, $plainPassword ) );

        return $this->sendResponse( $data, "Sikeres regisztráció." );
    }
}
