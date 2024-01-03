<?php

namespace App\Http\Controllers;

use App\Http\Resources\SharePostResource;
use App\Http\Resources\UserhResource;
use App\Models\Post;
use App\Models\SharePost;
use Illuminate\Http\Request;

class SharePostController extends Controller
{

    public function create(Request $request, Post $post)
    {
        $request->validate(['title' => 'required|max:255']);
        $postShared = SharePost::create(['title' => $request->title, 'post_id' => $post->id, 'share_by' => auth()->user()->id]);
        return response()->json(['status' => true,
            'message' => 'Post  Shared Successfully',
            'share' => new SharePostResource($postShared)], 201);
    }

    public function update(Request $request, SharePost $sharePost)
    {
        $request->validate(['title' => 'required|max:255',]);
        if ($request->title) {
            $sharePost->update(['title' => $request->title]);
        }
        return response()->json(['status' => true,'message' => 'Post  Shared  has Updated Successfully',
            'share' => new SharePostResource($sharePost)], 201);
    }

    public function delete(SharePost $sharePost)
    {
        $sharePost->delete();
        return response()->json(['status' => true, 'message' => ' share of post deleted successfully'], 201);
    }
}
