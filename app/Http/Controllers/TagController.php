<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use App\Http\Requests\Tags\StoreTagRequest;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class TagController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return $this->success("Successfully fetching all tags", "tags", Tag::all());
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return $this->success("Successfully created tag", "tag", $tag, 201);
    }

    public function show($id)
    {
        $tag = Tag::with("blogs")->find($id);

        if(!$tag) {
            return $this->notFound("orry not found tag");
        }

        return $this->success("Success", "tag", $tag);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);

        if(!$tag) {
            return $this->notFound("sorry not found tag");
        }

        $request->validate([
            'name' => 'required|string|unique:tags,name,' . $id . '|max:255',
        ]);

        $tag->update([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return $this->success("Successfully update tag", "tag", $tag);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);

        if(!$tag) {
            return $this->notFound("sorry not found tag");
        }

        $tag->delete();

        return $this->removeData("Tag deleted successfully");
    }
}
