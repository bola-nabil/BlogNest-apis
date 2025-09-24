<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $categories = Category::with('blogs')->get();

        return $this->success("Successfully fetching all categories", "categories", $categories);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->has('blogs')) {
            $category->blogs()->sync($request->blogs);
        }

        return $this->success("Successfully created category", "category", $category->load("blogs"), 201);
    }

    public function show($id)
    {
        $category = Category::with('blogs')
        ->find($id)
        ->paginate(10);

        if(!$category) {
            return $this->notFound("sorry not found category");
        }

        return $this->success("Success", "category", $category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::find($id);

        if(!$category) {
            return $this->notFound("sorry not found category");
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->has('blogs')) {
            $category->blogs()->sync($request->blogs);
        }

        return $this->success("Successfully updated category", "category", $category->load("blogs"));
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->notFound("sorry not found category");
        }

        $category->delete();

        return $this->removeData("Category deleted successfully");
    }
}
