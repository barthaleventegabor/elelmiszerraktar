<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\ResponseTrait;

class CategoryService {

    use ResponseTrait;

    public function __construct(){
    }

    public function getCategories() {
        $categories = Category::all();
        return $categories;
    }

    public function getCategory( Category $category ) {
        $category = Category::find( $category->id );
        return $category;
    }

    public function create( $data ) {

        $category = new Category();

        $category->name = $data[ "name" ];
        $category->description = $data[ "description" ];

        $category->save();

        return $category;
    }

    public function update( Category $category, $data ) {

        $category->name = $data[ "name" ];
        $category->description = $data[ "description" ];

        $category->save();

        return $category;
    }

    public function delete( Category $category ): bool {

        $category->delete();
        return true;
        
    }

    public function getCategoryId( $name ) {

        $category = Category::where( "name", $name )->first();
        $id = $category->id;

        return $id;
    }
}
