<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;

class SearchController extends Controller
{
     public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Query is required'], 400);
        }

        $blogs = Blog::with(['user:id,name,profile_image']) // 👈 load related user
            ->where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'title', 'created_at', 'content', 'user_id']);        $categories = Category::where('name', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name']);

        $tags = Tag::where('name', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name']);

        return response()->json([
            'blogs' => $blogs,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
