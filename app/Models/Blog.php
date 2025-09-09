<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title",
        "slug",
        "content",
        "image",
        "status"
    ];

    protected $hidden = [
        "user_id",
        "slug",
        "status",
        "created_at",
        "updated_at"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, "blog_category");
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, "blog_tag");
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }
}
