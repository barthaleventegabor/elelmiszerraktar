<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Traits\ResponseTrait;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    use ResponseTrait;
    public function __construct( protected ProductService $productService ){}

    public function getProducts()
    {
        $products = $this->productService->getProducts();
        return $this->sendResponse($products, "Termékek lekérve.");
    }

    public function getProduct( Product $product )
    {
        $product = $this->productService->getProduct($product);
        return $this->sendResponse($product, "Termék lekérve.");
    }

    public function create(ProductRequest $request, ProductService $productService){
        Gate::authorize("create", Product::class);

        $validated = $request->validated();

        $product = $productService->create($validated);
        return $this->sendResponse($product, "Termék létrehozva.");
    }

    public function update(ProductRequest $request, Product $product, ProductService $productService){
        Gate::authorize("update", $product);

        $validated = $request->validated();

        $product = $productService->update($product, $validated);
        return $this->sendResponse($product, "Termék frissítve.");
    }

    public function delete(Product $product, ProductService $productService){
        Gate::authorize("delete", $product);

        return $this->sendResponse($productService->delete($product), "Termék törölve.");
    }

    
}
