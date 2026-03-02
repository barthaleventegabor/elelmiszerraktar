<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ResponseTrait;
use App\Http\Resources\ProductResource;

class ProductService
{
    use ResponseTrait;

    protected CategoryService $categoryService;
    protected SupplierService $supplierService;

    public function __construct(CategoryService $categoryService, SupplierService $supplierService){
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function getProducts(){
        $products = Product::with("category", "supplier")->get();
        return (ProductResource::collection($products)) ;
    }

    public function getProduct(Product $product){
        $product = Product::with("category", "supplier")->find($product->id);
        return (new ProductResource($product)) ;
    }

    public function create($data): ProductResource {
        $product = new Product();

        $product->name = $data["name"];
        $product->description = $data["description"];
        $product->category_id = $this->categoryService->getCategoryId($data["category_name"]) ;
        $product->supplier_id = $this->supplierService->getSupplierId($data["supplier_name"]);
        $product->quantity = $data["quantity"];
        $product->unit = $data["unit"];
        $product->expiration_date = $data["expiration_date"];

        $product->save();
        
        return (new ProductResource( $product ));
        
    }

    public function update(Product $product, $data): ProductResource {
        $product->name = $data["name"];
        $product->description = $data["description"];
        $product->category_id = $this->categoryService->getCategoryId($data["category_name"]);
        $product->supplier_id = $this->supplierService->getSupplierId($data["supplier_name"]);
        $product->quantity = $data["quantity"];
        $product->unit = $data["unit"];
        $product->expiration_date = $data["expiration_date"];

        $product->save();
        
        return (new ProductResource( $product ));
    }

    public function delete(Product $product): bool {
        $product->delete();
        return true;
    }
    

}