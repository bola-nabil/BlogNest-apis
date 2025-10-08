<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Http\Requests\Blogs\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UploadFiles;

class BlogController extends Controller
{
    use UploadFiles;
    public function index()
    {
        $blogs = Blog::with(['user', 'categories', 'tags'])
        ->withCount(['likes', 'comments'])
        ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Blogs fetching successfully',
            'blogs' => $blogs,
        ]);
    }

    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), "uploads/blogs");
        }

         $blog = Blog::create($data);

        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }
        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'blog' => $blog->load(['categories', 'tags']),
        ], 201);
    }

    public function show($id)
    {
        $blog = Blog::with(['user', 'categories', 'tags'])
        ->withCount(['likes', 'comments'])
        ->findOrFail(id: $id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully fetching data',
            'blog' => $blog,
        ]);
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // delete old image
            if ($blog->image) {
                $this->deleteImage($blog->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/blogs');
        }

        $blog->update($data);

        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }
        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'blog' => $blog->load(['categories', 'tags']),
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image) {
            $this->deleteImage($blog->image);
        }
        
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully',
        ]);
    }
}
