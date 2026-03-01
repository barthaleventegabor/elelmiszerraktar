<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\ResponseTrait;

class CategoryService {

    use ResponseTrait;

    public function __construct(){
    }

    public function getCategoryId( $name ) {

        $category = Category::where( "name", $name )->first();
        $id = $category->id;

        return $id;
    }
}
