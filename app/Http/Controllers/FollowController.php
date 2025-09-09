<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserFollowedNotification;
use App\Traits\ApiResponse;

class FollowController extends Controller
{
    use ApiResponse;
    public function follow($id)
    {
        $userToFollow = User::find($id);
        $user = auth()->user();

        if(!$userToFollow) {
            return $this->notFound("sorry not found user");
        }

        if ($user->id == $userToFollow->id) {
            return $this->error("You can't follow yourself", 401);
        }

        if (!$user->followings()->where('following_id', $userToFollow->id)->exists()) {
            $user->followings()->attach($userToFollow->id);

            $userToFollow->notify(new UserFollowedNotification($user));
            return $this->success("Success", "message", "You are now following {$userToFollow->name}");
        }

        return $this->success("Success", "message", "You already follow {$userToFollow->name}");
    }

    public function unfollow($id)
    {
        $userToFollow = User::find($id);
        $user = auth()->user();

        if(!$userToFollow) {
            return $this->notFound("sorry not found user");
        }

        if ($user->followings()->where('following_id', $userToFollow->id)->exists()) {
            $user->followings()->detach($userToFollow->id);

            return $this->success("Success", "message", "You unfollowed {$userToFollow->name}");
        }

        return $this->success("Success", "message", "You are not following {$userToFollow->name}");
    }

    public function followers($id)
    {
        $user = User::find($id);

        if(!$user) {
            return $this->notFound("sorry not found user");
        }

        return $this->success("successfully fetching followers data", "followers", $user->followers);
    }

    public function followings($id)
    {
        $user = User::find($id);

        if(!$user) {
            return $this->notFound("sorry not found user");
        }

        return $this->success("successfully fetching followings data", "followings", $user->followings);
    }
}
