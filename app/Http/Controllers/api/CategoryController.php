<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Traits\ResponseTrait;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function __construct( protected CategoryService $categoryService ){}

    public function getCategories()
    {
        Gate::authorize("viewAny", Category::class);
        $categories = $this->categoryService->getCategories();
        return $this->sendResponse($categories, "Kategóriák lekérve.");
    }

    public function getCategory( Category $category )
    {
        Gate::authorize("view", $category);
        $category = $this->categoryService->getCategory($category);
        return $this->sendResponse($category, "Kategória lekérve.");
    }

    public function create(CategoryRequest $request, CategoryService $categoryService){
        Gate::authorize("create", Category::class);

        $validated = $request->validated();

        $category = $categoryService->create($validated);
        return $this->sendResponse($category, "Kategória létrehozva.");
    }

    public function update(CategoryRequest $request, Category $category, CategoryService $categoryService){
        Gate::authorize("update", $category);

        $validated = $request->validated();

        $category = $categoryService->update($category, $validated);
        return $this->sendResponse($category, "Kategória frissítve.");
    }

    public function delete(Category $category, CategoryService $categoryService){
        Gate::authorize("delete", $category);

        return $this->sendResponse($categoryService->delete($category), "Kategória törölve.");
    }
}
