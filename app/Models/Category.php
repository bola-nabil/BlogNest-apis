<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "blogs",
    ];

    protected $hidden = [
        "slug",
        "created_at",
        "updated_at"
    ];

    public function blogs() {
        return $this->belongsToMany(Blog::class, "blog_category");
    }
}
