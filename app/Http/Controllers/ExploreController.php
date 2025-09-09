<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Traits\ApiResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ExploreController extends Controller
{
    use ApiResponse;
    public function trending(Request $request)
    {
        $query = Blog::with(['user', 'categories', 'tags'])
                    ->withCount('likes', 'comments');

        $sort = $request->get('sort', 'most_liked');

        switch($sort) {
            case 'most_commented':
                $query->orderBy('comments_count', 'desc');
                break;
            
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;

            default:
                $query->orderBy('likes_count', 'desc');
        }

        $blogs = $query->paginate(10);

        return $this->success("successfully fetching data", "explore", $blogs);
    }
}
