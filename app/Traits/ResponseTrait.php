<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait {
    
    public function sendResponse( $data, $message = "" ) {

        $response = [

            "data" => $data,
            "message" => $message,
            "success" => true
        ];

        return response()->json( $response, 200 );
    }

    public function sendError( $error, $errorMessage = [], $code = 404 ) {

        $response = [

            "success" => false,
            "error" => $error
        ];

        if( !empty( $errorMessage )) {

            $response[ "errorMessage" ] = $errorMessage;
        }

        return response()->json( $response, $code );
    }

    protected function sendValidationError( $validatorErrors ): JsonResponse {

        $error = "Adatbeviteli hiba";

        return $this->sendError( $error, $validatorErrors, 422 );
    }
}
