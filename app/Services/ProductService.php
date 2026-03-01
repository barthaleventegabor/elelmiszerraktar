<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ResponseTrait;

class ProductService
{
    use ResponseTrait;

    protected CategoryService $categoryService;
    protected SupplierService $supplierService;

    public function __construct(CategoryService $categoryService, SupplierService $supplierService){
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function create($data){
        $product = new Product();

        $product->name = $data["name"];
        $product->description = $data["description"];
        $product->category_id = $this->categoryService->getCategoryId($data["category_name"]) ;
        $product->supplier_id = $this->supplierService->getSupplierId($data["supplier_name"]);
        $product->quantity = $data["quantity"];
        $product->unit = $data["unit"];
        $product->expiration_date = $data["expiration_date"];

        $product->save();
        
        return $this->sendResponse($product, "Termék létrehozva.");
        
    }

    public function update(Product $product, $data){
        $product->name = $data["name"];
        $product->description = $data["description"];
        $product->category_id = $this->categoryService->getCategoryId($data["category_name"]);
        $product->supplier_id = $this->supplierService->getSupplierId($data["supplier_name"]);
        $product->quantity = $data["quantity"];
        $product->unit = $data["unit"];
        $product->expiration_date = $data["expiration_date"];

        $product->save();
        
        return $this->sendResponse($product, "Termék frissítve.");
    }

    public function delete(Product $product){
        $product->delete();
        return $this->sendResponse(null, "Termék törölve.");
    }
    

}