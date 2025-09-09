<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use App\Notifications\BlogLikedNotification;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class LikeController extends Controller
{
    use ApiResponse;
    /**
     * Toggle like/unlike on a blog.
     */
    public function toggle($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $user = auth()->user();

        $existingLike = Like::where('blog_id', $blog->id)
                            ->where('user_id', $user->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return $this->success("Success", "message", "You unliked the blog: {$blog->title}");
        }

        Like::create([
            'blog_id' => $blog->id,
            'user_id' => $user->id,
        ]);

        if ($blog->user_id !== $user->id) {
            $blog->user->notify(new BlogLikedNotification($blog, $user));
        }

        return $this->success("Success", "message", "You liked the blog: {$blog->title}");
    }

    /**
     * Get all likes for a blog.
     */
    public function index($blogId)
    {
        $blog = Blog::with('likes.user')->findOrFail($blogId);

        return $this->success("Success", "data", [
            'likes_count' => $blog->likes->count(),
            'likes' => $blog->likes
        ]);
    }
}
