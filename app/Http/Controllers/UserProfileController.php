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

    $authUser = auth()->user();

    // check if logged-in user already follows this profile
    $isFollowing = false;
    if ($authUser && $authUser->id !== $user->id) {
        $isFollowing = $authUser->followings()->where('followed_id', $user->id)->exists();
    }

    // add is_following flag
    $user->is_following = $isFollowing;

    return $this->success("Successfully fetching user profile", "user", $user);
}
}
