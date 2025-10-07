<?php

namespace App\Http\Requests\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "title" => "required|string|max:255",
            "content" => "required|string",
            "category_id" => "nullable|exists:categories,id",
            "tags" => "nullable|array",
            "tags.*" => "exists:tags,id",
            "image" => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048",
            "slug" => "nullable|string",
        ];
    }
}
