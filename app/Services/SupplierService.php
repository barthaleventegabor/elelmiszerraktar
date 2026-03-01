<?php

namespace App\Services;

use App\Models\Supplier;
use App\Traits\ResponseTrait;

class SupplierService {

    use ResponseTrait;

    public function __construct(){
    }

    public function getSupplierId( $name ) {

        $supplier = Supplier::where( "name", $name )->first();
        $id = $supplier->id;

        return $id;
    }
}
