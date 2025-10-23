<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserFollowedNotification;
use App\Traits\ApiResponse;

class FollowController extends Controller
{
    use ApiResponse;

    /**
     * Follow a user
     */
    public function follow($id)
    {
        $authUser = auth()->user();
        $userToFollow = User::find($id);

        if (!$userToFollow) {
            return $this->notFound("User not found");
        }

        if ($authUser->id === $userToFollow->id) {
            return $this->error("You can't follow yourself", 400);
        }

        $alreadyFollowing = $authUser->followings()
            ->where('following_id', $userToFollow->id)
            ->exists();

        if ($alreadyFollowing) {
            return $this->success("Success", "message", "You already follow {$userToFollow->name}");
        }

        // Attach the following
        $authUser->followings()->attach($userToFollow->id);

        // Notify the followed user
        $userToFollow->notify(new UserFollowedNotification($authUser));

        return $this->success("Success", "message", "You are now following {$userToFollow->name}");
    }

    /**
     * Unfollow a user
     */
    public function unfollow($id)
    {
        $authUser = auth()->user();
        $userToUnfollow = User::find($id);

        if (!$userToUnfollow) {
            return $this->notFound("User not found");
        }

        $isFollowing = $authUser->followings()
            ->where('following_id', $userToUnfollow->id)
            ->exists();

        if (!$isFollowing) {
            return $this->success("Success", "message", "You are not following {$userToUnfollow->name}");
        }

        // Detach the following
        $authUser->followings()->detach($userToUnfollow->id);

        return $this->success("Success", "message", "You unfollowed {$userToUnfollow->name}");
    }

    /**
     * Get followers list for a user
     */
    public function followers($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound("User not found");
        }

        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.profile_image')
            ->get();

        return $this->success("Followers fetched successfully", "followers", $followers);
    }

    /**
     * Get followings list for a user
     */
    public function followings($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound("User not found");
        }

        $followings = $user->followings()
            ->select('users.id', 'users.name', 'users.username', 'users.profile_image')
            ->get();

        return $this->success("Followings fetched successfully", "followings", $followings);
    }
}
