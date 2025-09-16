<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['user', 'categories', 'tags', 'likes', 'comments'])->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Blogs fetching successfully',
            'blogs' => $blogs,
        ]);
    }

    public function store(StoreBlogRequest $request)
    {
        $blog = Blog::create($request->validated());

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
        $blog = Blog::with(['user', 'categories', 'tags'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully fetching data',
            'blog' => $blog,
        ]);
    }

    public function update(StoreBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update($request->validated());

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
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully',
        ]);
    }
}
