<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::middleware("auth:api")->prefix('v1')->group(function() {
    Route::post("/logout", [AuthController::class, "logout"]);
    //blogs
    Route::get("/blogs", [BlogController::class, "index"]);
    Route::post("/blogs", [BlogController::class, "store"]);
    Route::get("/blogs/{id}", [BlogController::class, "show"]);
    Route::put("/blogs/{id}", [BlogController::class, "update"]);
    Route::delete("/blogs/{id}", [BlogController::class, "destroy"]);

    // comments
    Route::get("blogs/{blogId}/comments", [CommentController::class, "index"]);
    Route::post("blogs/{blogId}/comments", [CommentController::class, "store"]);
    Route::get("comments/{id}", [CommentController::class, "show"]);
    Route::put("comments/{id}", [CommentController::class, "update"]);
    Route::delete("comments/{id}", [CommentController::class, "destroy"]);

    //likes
    Route::get("blogs/{blogId}/likes", [LikeController::class, "index"]);
    Route::post("blogs/{blogId}/like", [LikeController::class, "toggle"]);

    // categories
    Route::get("categories", [CategoryController::class, "index"]);
    Route::post("categories", [CategoryController::class, "store"]);
    Route::get("categories/{id}", [CategoryController::class, "show"]);
    Route::put("categories/{id}", [CategoryController::class, "update"]);
    Route::delete("categories/{id}", [CategoryController::class, "destroy"]);

    // tags
    Route::get("tags", [TagController::class, "index"]);
    Route::post("tags", [TagController::class, "store"]);
    Route::get("tags/{id}", [TagController::class, "show"]);
    Route::put("tags/{id}", [TagController::class, "update"]);
    Route::delete("tags/{id}", [TagController::class, "destroy"]);


    // user profile
    Route::get("/profile", [UserProfileController::class, "index"]);
    Route::get("/users/{id}/profile", [UserProfileController::class, "show"]);
    Route::post("/profile/update", [ProfileController::class, 'update']);
    Route::get("/profile/{id}", [ProfileController::class, 'show']);

    // bookmarks
    Route::post("/blogs/{id}/bookmarks", [BookmarkController::class, 'toggle']);
    Route::get("/user/bookmarks", [BookmarkController::class, 'index']);

    // notifications
    Route::get("/notifications", [NotificationController::class, 'index']);
    Route::post("/notifications/{id}/read", [NotificationController::class, 'markAsRead']);
    Route::post("/notifications/read-all", [NotificationController::class, 'markAllAsRead']);

    // followers
    Route::post("/users/{id}/follow", [FollowController::class, 'follow']);
    Route::delete("/users/{id}/unfollow", [FollowController::class, 'unfollow']);
    Route::get("/users/{id}/followers", [FollowController::class, 'followers']);
    Route::get("/users/{id}/followings", [FollowController::class, 'followings']);

    // feed
    Route::get("/feed", [FeedController::class, 'index']);
    Route::get("/explore", [ExploreController::class, 'trending']);
});