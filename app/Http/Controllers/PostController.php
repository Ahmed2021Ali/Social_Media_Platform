<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /* create post */
    public function create(CreatePostRequest $request)
    {
        if ($request['description'] || $request['files']) {
            $post = Post::create(['description' => $request->description, 'user_id' => auth()->user()->id]);
            if ($request['files']) {
                foreach ($request['files'] as $file) {
                    $post->addMedia($file)->toMediaCollection('postsFiles');
                }
            }
            return response()->json(['status' => true, 'message' => 'Post  created Successfully', 'Post' => new PostResource($post)], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'post  required'], 400);
        }
    }

    /* Who can modify the post is the one who created it only */

    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($request->description) {
            $post->update(['description' => $request->description]);
        }
        if ($request['files']) {
            $post->media()->delete();
            foreach ($request['files'] as $file) {
                $post->addMedia($file)->toMediaCollection('postsFiles');
            }
        }
        return response()->json(['status' => true, 'message' => 'Post  Update Successfully', 'Post' => new PostResource($post)], 201);
    }

    /* Who can delete the post is the one who created it only */

    public function delete(Request $request, Post $post)
    {
        $post->delete();
        return response()->json(['status' => true, 'message' => 'post delete successfully'], 201);
    }

}
