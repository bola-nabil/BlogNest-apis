<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Traits\ApiResponse;

class FeedController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $user = auth()->user();

        $followingIds = $user->followings()->pluck("users.id");

        $blogs = Blog::with(['user', 'categories', 'tags'])
                    ->withCount(['likes', 'comments'])
                    ->whereIn('user_id', $followingIds)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return $this->success("successfully feeding data", "feed", $blogs);
    }
}
