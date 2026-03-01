<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Traits\ResponseTrait;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    use ResponseTrait;
    

    public function getProducts()
    {
        $products = Product::with("category", "supplier")->get();
        return $this->sendResponse(ProductResource::collection($products), "Termékek lekérve.");
    }

    public function getProduct( Product $product )
    {
        $product = Product::with("category", "supplier")->find( $product->id );
        return $this->sendResponse( new ProductResource( $product ), "Termék lekérve." );
    }

    public function create(ProductRequest $request, ProductService $productService){
        Gate::authorize("create", Product::class);

        $validated = $request->validated();

        return $productService->create($validated);
    }

    public function update(ProductRequest $request, Product $product, ProductService $productService){
        Gate::authorize("update", $product);

        $validated = $request->validated();

        return $productService->update($product, $validated);
    }

    public function delete(Product $product, ProductService $productService){
        Gate::authorize("delete", $product);

        return $productService->delete($product);
    }

    
}
