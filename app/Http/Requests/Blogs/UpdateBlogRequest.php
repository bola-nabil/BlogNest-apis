<?php

namespace App\Http\Requests\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "title" => "nullable|string|max:255",
            "content" => "nullable|string",
            "categories" => "nullable|array",
            "categories.*" => "exists:categories,id",
            "tags" => "nullable|array",
            "tags.*" => "exists:tags,id",
            "image" => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048",
            "slug" => "nullable|string",
        ];
    }
}
