<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponse;

class UserProfileController extends Controller
{
    use ApiResponse;

public function index()
{
    $authUser = auth()->user();

    $users = User::withCount(['followers', 'followings'])
        ->with('blogs')
        ->where('id', '!=', $authUser->id) // optional: exclude the current user
        ->get()
        ->map(function ($user) use ($authUser) {
            return [
                "id" => $user->id,
                "name" => $user->name,
                "bio" => $user->bio,
                "location" => $user->location,
                "website" => $user->website,
                "profile_image" => $user->profile_image,
                "followers_count" => $user->followers_count,
                "followings_count" => $user->followings_count,
                "is_following" => $user->followers()
                    ->where('follower_id', $authUser->id)
                    ->exists(),
            ];
        });

        return $this->success("Success", "users", $users);
    }

    public function show($id)
    {
        $user = User::withCount(['followers', 'followings'])
                    ->with(['blogs', 'bookmarks'])
                    ->find($id);

        if (!$user) {
            return $this->notFound("sorry not found user");
        }

        // Check if logged-in user follows this profile
        $isFollowing = auth()->check() 
            ? $user->followers()->where('follower_id', auth()->id())->exists()
            : false;

        return $this->success("Success", "user", [
            "id" => $user->id,
            "name" => $user->name,
            "bio" => $user->bio,
            "location" => $user->location,
            "website" => $user->website,
            "profile_image" => $user->profile_image,
            "followers_count" => $user->followers_count,
            "followings_count" => $user->followings_count,
            "is_following" => $isFollowing,
            "blogs" => $user->blogs,          
            "bookmarks" => $user->bookmarks,
            ]);
    }

}
