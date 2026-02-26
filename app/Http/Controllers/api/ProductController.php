<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Traits\ResponseTrait;

class ProductController extends Controller
{
    use ResponseTrait;
    public function getProducts()
    {
        $products = Product::all();
        return $this->sendResponse($products);
    }
}
