<?php

namespace App\Services;

use App\Models\Supplier;
use App\Traits\ResponseTrait;

class SupplierService {

    use ResponseTrait;

    public function __construct(){
    }

    public function getSuppliers() {
        $suppliers = Supplier::all();
        return $suppliers;
    }

    public function getSupplier( Supplier $supplier ) {
        $supplier = Supplier::find( $supplier->id );
        return $supplier;
    }

    public function create( $data ) {

        $supplier = new Supplier();

        $supplier->name = $data[ "name" ];
        $supplier->email = $data[ "email" ];
        $supplier->phone = $data[ "phone" ];
        $supplier->address = $data[ "address" ];

        $supplier->save();

        return $supplier;
    }

    public function update( Supplier $supplier, $data ) {

        $supplier->name = $data[ "name" ];
        $supplier->email = $data[ "email" ];
        $supplier->phone = $data[ "phone" ];
        $supplier->address = $data[ "address" ];

        $supplier->save();

        return $supplier;
    }

    public function delete( Supplier $supplier ): bool {

        $supplier->delete();
        return true;
        
    }

    public function getSupplierId( $name ) {

        $supplier = Supplier::where( "name", $name )->first();
        $id = $supplier->id;

        return $id;
    }

}
