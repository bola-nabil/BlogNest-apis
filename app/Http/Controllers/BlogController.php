<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\Blogs\StoreBlogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UploadFiles;
use App\Traits\ApiResponse;

class BlogController extends Controller
{
    use UploadFiles, ApiResponse;

    public function index(Request $request)
    {
        $query = Blog::with(['user', 'categories', 'tags']);

        if($request->has("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'most_liked':
                    $query->withCount('likes')->orderBy('likes_count', 'desc');
                    break;
                case 'most_commented':
                    $query->withCount('comments')->orderBy('comments_count', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $blogs = $query->paginate(10);

        return $this->success("Blogs fetching successfully","blogs",$blogs, );
    }

    public function store(StoreBlogRequest $request)
    {
        $data = [
            "title" => $request->title,
            "content" => $request->content,
            "slug" => Str::slug($request->title),
            "user_id" => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'images');
        }

        $blog = Blog::create($data);

        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return $this->success("created successfully","blog", $blog->load(["user", "categories", "tags"]), 201);
    }

    public function show($id)
    {
        $blog = Blog::with(["user", "categories", "tags"])->find($id);

        return $this->notFound($blog);
        return $this->success("Successfully fetching data","blog", $blog);
    }

    public function update(StoreBlogRequest $request, $id)
    {
        $blog = Blog::find($id);

        if(!$blog) {
            return $this->notFound("sorry blog not found");
        }

        if($blog->user_id !== auth()->id()) {
            return $this->error("Unauthorized", 403);
        }

        $data = [
            "title" => $request->title,
            "content" => $request->content,
            "slug" => Str::slug($request->title),
        ];

        if($request->hasFile('image')) {
            if($blog->image) {
                $this->deleteImage($blog->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'images');
        }

        $blog->update($data);

        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return $this->success("Blog Updated Successfuly","blog", $blog->load(["user", "categories", "tags"]),);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        if(!$blog) {
            return $this->notFound("sorry blog not found");
        }

        if($blog->user_id !== auth()->id()) {
            return $this->error("Unauthorized", 403);
        }

        if($blog->image) {
            $this->deleteImage($blog->image);
        }

        $blog->delete();
        return $this->removeData("blog removed successfully");
    }
}
