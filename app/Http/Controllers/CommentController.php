<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /* create Comment */
    public function create(CreateCommentRequest $request, Post $post)
    {
        if (isset($request['title']) || isset($request['files'])) {
            $comment = Comment::create(['title' => $request->title, 'user_id' => auth()->user()->id, 'post_id' => $post->id]);
            uploadFiles($request['files'], $comment, 'commentFiles');
            return response()->json(['status' => true, 'message' => ' Comment created Successfully', 'Comment' => new CommentResource($comment)], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'Comment required'], 402);
        }
    }

    /* Who can modify the Comment is the one who created it only */

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        if ($request->title) {
            $comment->update(['title' => $request->title]);
        }
        updateFiles($request['files'], $comment, 'commentFiles');
        return response()->json(['status' => true, 'message' => ' Comment  Update Successfully', 'Comment' => new CommentResource($comment)], 201);
    }

    /* Who can delete the comment is the one who created it only */

    public function delete(Comment $comment)
    {
        $comment->delete();
        return response()->json(['status' => true, 'message' => 'comment delete successfully'], 201);
    }

    /* View all the comments of one of the posts */
    public function show(Post $post)
    {
        return response()->json(['status' => true, 'post' => new PostResource($post), 'comments' => CommentResource::collection($post->comments)], 200);
    }
}
