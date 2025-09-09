<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponse;

class UserProfileController extends Controller
{
    use ApiResponse;
    public function show($id)
    {
        $user = User::with([
            'blogs.categories', 
            'blogs.tags',
            'comments.blog',
            'likes.blog'
        ])->findOrFail($id);

        return $this->success("Successfully fetching user profile", "user", $user);
    }
}
