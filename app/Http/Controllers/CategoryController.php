<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Categories\StoreCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CategoryController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return $this->success("success fetching categories data", "categories", Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return $this->success("successfully created category", "category", $category, 201);
    }

    public function show($id)
    {
        $category = Category::with("blogs")->find($id);

        if(!$category) {
            return $this->notFound("sorry category not found");
        } 

        return $this->success("successfully fetching data", "category", $category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->notFound("sorry category not found");
        }

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id . '|max:255',
        ]);

        $category->update([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return $this->success("successfully updating data", "category", $category);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->notFound("sorry category not found");
        }
        $category->delete();

        return $this->removeData("Category deleted successfully");
    }
}
