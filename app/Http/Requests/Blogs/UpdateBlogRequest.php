<?php

namespace App\Http\Requests\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => "nullable|string|max:255",
            "content" => "nullable|string",
            "category_id" => "nullable|exists:categories,id",
            "tags" => "nullable|array",
            "tags.*" => "exists:tags,id",
            "image" => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048",
            "slug" => "nullable|string",
        ];
    }
}
