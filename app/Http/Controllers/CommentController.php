<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Traits\ApiResponse;

class CommentController extends Controller
{
    use ApiResponse;
    public function index($blogId)
    {
        $comment = Comment::with("user")->where("blog_id", $blogId)->get();
        return $this->success("successfully fetching data", "comment", $comment);
    }

    public function store(StoreCommentRequest $request, $blogId)
    {
        $comment = Comment::create([
            "comment" => $request->comment,
            "user_id" => auth()->id(),
            "blog_id" => $blogId,
        ]);

        return $this->success("comment created successfully", "comment", $comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::with("user")->find($id);

        if(!$comment) {
            return $this->notFound("sorry comment not found");
        }

        return $this->success("successfully fetching data", "comment", $comment);
    }

    public function update(StoreCommentRequest $request, $id)
    {
        $comment = Comment::with("user")->find($id);

        if(!$comment) {
            return $this->notFound("sorry comment not found");
        }

        if($comment->user_id !== auth()->id()) {
            return $this->error("Unauthorized", 401);
        }

        $comment->update($request->only("comment"));

        return $this->success("successfully updating comment", "comment", $comment);
    }

    public function destroy($id)
    {
        $comment = Comment::with("user")->find($id);

        if(!$comment) {
            return $this->notFound("sorry comment not found");
        }

        if($comment->user_id !== auth()->id()) {
            return $this->error("Unauthorized", 403);
        }

        $comment->delete();

        return $this->removeData("Comment Deleted Successfully");
    }
}
