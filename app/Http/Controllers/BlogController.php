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
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Blogs fetched successfully',
            'blogs' => $blogs,
        ]);
    }

    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/blogs');
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
        $blog = Blog::with(['user', 'categories', 'tags', 'likes'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);

        $user = auth()->user();

        // Check if logged-in user liked this blog
        $liked = false;
        if ($user) {
            $liked = $blog->likes()->where('user_id', $user->id)->exists();
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog fetched successfully',
            'blog' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'content' => $blog->content,
                'image' => $blog->image,
                'user' => $blog->user,
                'categories' => $blog->categories,
                'tags' => $blog->tags,
                'likes_count' => $blog->likes_count,
                'comments_count' => $blog->comments_count,
                'liked' => $liked,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ],
        ]);
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($blog->image) {
                $this->deleteImage($blog->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'uploads/blogs');
        }

        if (!empty($request->title)) {
            $data['slug'] = Str::slug($request->title);
        }

        $blog->update($data);

        if ($request->filled('categories')) {
            $blog->categories()->sync($request->categories);
        }
        if ($request->filled('tags')) {
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
