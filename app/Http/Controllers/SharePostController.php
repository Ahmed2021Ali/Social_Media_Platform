<?php

namespace App\Http\Controllers;

use App\Http\Resources\SharePostResource;
use App\Http\Resources\UserhResource;
use App\Models\Post;
use App\Models\SharePost;
use Illuminate\Http\Request;

class SharePostController extends Controller
{

    public function create(Request $request, $id)
    {
        $request->validate(['title' => 'required|max:255',]);
        $post = Post::find($id);
        if ($post) {
            $postShared = SharePost::create([
                'title' => $request->title,
                'post_id' => $post->id,
                'share_by' => auth()->user()->id
            ]);
            return response()->json(['status' => true, 'message' => 'Post  Shared Successfully',
                'share'=>new SharePostResource($postShared)], 201);
        } else {
            return response()->json(['status' => false, 'message' => ' Post  Not found'], 402);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'required|max:255',]);
        $postShared = SharePost::find($id);
        if ($postShared) {
            if ($request->title) {
                $postShared->update(['title' => $request->title]);
            }
            return response()->json(['status' => true, 'message' => 'Post  Shared  has Updated Successfully',
                'share'=>new SharePostResource($postShared)], 201);
        } else {
            return response()->json(['status' => false, 'message' => ' Post has Shared Not found'], 402);
        }
    }

    public function delete($id)
    {
        $postShared = SharePost::find($id);
        if ($postShared) {
            $postShared->delete();
            return response()->json(['status' => true, 'message' => ' share of post deleted successfully',
                'share By' => new UserhResource(auth()->user())], 201);
        } else {
            return response()->json(['status' => false, 'message' => ' Post has Shared Not found'], 402);
        }
    }
}
