<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Supplier;
use App\Traits\ResponseTrait;
use App\Services\SupplierService;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    use ResponseTrait;
    public function __construct( protected SupplierService $supplierService ){}

    public function getSuppliers()
    {
        Gate::authorize("viewAny", Supplier::class);
        $suppliers = $this->supplierService->getSuppliers();
        return $this->sendResponse($suppliers, "Beszállítók lekérve.");
    }

    public function getSupplier( Supplier $supplier )
    {
        Gate::authorize("view", $supplier);
        $supplier = $this->supplierService->getSupplier($supplier);
        return $this->sendResponse($supplier, "Beszállító lekérve.");
    }

    public function create(SupplierRequest $request, SupplierService $supplierService){
        Gate::authorize("create", Supplier::class);

        $validated = $request->validated();

        $supplier = $supplierService->create($validated);
        return $this->sendResponse($supplier, "Beszállító létrehozva.");
    }

    public function update(SupplierRequest $request, Supplier $supplier, SupplierService $supplierService){
        Gate::authorize("update", $supplier);

        $validated = $request->validated();

        $supplier = $supplierService->update($supplier, $validated);
        return $this->sendResponse($supplier, "Beszállító frissítve.");
    }

    public function delete(Supplier $supplier, SupplierService $supplierService){
        Gate::authorize("delete", $supplier);

        return $this->sendResponse($supplierService->delete($supplier), "Beszállító törölve.");
    }
}
