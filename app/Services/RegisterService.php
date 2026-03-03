<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ResponseTrait;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\UserProfile;

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

        $profile = new UserProfile();
        $profile->full_name = $data[ "full_name" ];
        $profile->city = $data[ "city" ] ?? null;
        $profile->address = $data[ "address" ] ?? null;
        $profile->phone = $data[ "phone" ] ?? null;
        $profile->user_id = $user->id;        
        $profile->save();

        Mail::to( $user->email )->send( new WelcomeMail( $user, $plainPassword ) );

        return $this->sendResponse( $data, "Sikeres regisztráció." );
    }
}
