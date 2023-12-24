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
    public function create(CreateCommentRequest $request, $id)
    {
        $data = $request->validated();
        if (isset($data['title']) || isset($data['files'])) {
            $comment = Comment::create(['title' => $request->title, 'user_id' => auth()->user()->id, 'post_id' => $id]);
            if ($request->files) {
                foreach ($request->files as $file) {
                    foreach ($file as $f) {
                        $comment->addMedia($f)->toMediaCollection('commentFiles');
                    }
                }
            }
            return response()->json(['status' => true,'message' => ' Comment  created Successfully', 'Comment' => new CommentResource($comment)], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'Comment required'], 402);
        }
    }

    /* Who can modify the Comment is the one who created it only */

    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            if ($request->title) {
                $comment->update(['title' => $request->title]);
            }
            if ($request->files) {
                $comment->media()->delete();
                foreach ($request->files as $file) {
                    foreach ($file as $f) {
                        $comment->addMedia($f)->toMediaCollection('commentFiles');
                    }
                }
            }
            return response()->json(['status' => true,'message' => ' Comment  Update Successfully', 'Comment' => new CommentResource($comment)], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'Comment Not Found'], 402);
        }
    }

    /* Who can delete the comment is the one who created it only */

    public function delete(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            return response()->json(['status' => true, 'message' => 'comment delete successfully'], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'comment Not Found'], 402);
        }
    }

    /* View all the comments of one of the posts */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        if ($post) {
            $comments = Comment::where('post_id', $id)->get();
            return response()->json(['status' => true, 'post' => new PostResource($post),
                'comments' => CommentResource::collection($comments)], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'post Not found'], 402);
        }
    }
}
