<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Traits\ApiResponse;

class BookmarkController extends Controller
{
    use ApiResponse;
    public function toggle($blogId)
    {
        $user = auth()->user();
        $blog = Blog::findOrFail($blogId);

        if($user->bookmarks()->where('blog_id', $blogId)->exists()) {
            $user->bookmarks()->detach($blogId);
            return $this->success("Success", "message", "Bookmark removed");
        } else {
            $user->bookmarks()->attach($blogId);
            return $this->success("Success", "message", "Blog bookmarked");
        }
    }

    public function index()
    {
        $user = auth()->user();

        $bookmarks = $user->bookmarks()->with('user', 'categories', 'tags')->paginate(10);

        return $this->success("Success",  "bookmarks", $bookmarks);
    }
}
