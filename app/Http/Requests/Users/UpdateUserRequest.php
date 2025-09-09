<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "name" => "sometimes|string|max:255",
            "bio" => "nullable|string|max:500",
            "location" => "nullable|string|max:255",
            "website" => "nullable|url|max:255",
            "profile_image" => "nullable|image|mimes:jpg,jpeg,png|max:2048"
        ];
    }
}
