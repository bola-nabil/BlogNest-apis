<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UpdateUserRequest;
use App\Traits\UploadFiles;
use App\Traits\ApiResponse;
use App\Models\User;

class ProfileController extends Controller
{
    use UploadFiles, ApiResponse;

    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();

        $data = $request->only(['name', 'bio', 'location', 'website']);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $this->deleteImage($user->profile_image);
            }
            $data['profile_image'] = $this->uploadImage($request->file('profile_image'), 'images');
        }

        $user->update($data);

        return $this->success("Profile updated successfully", "user", $user);
    }

    public function show($id)
    {
        $user = User::withCount(['followers', 'followings'])->find($id);

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
        ]);
    }
}
