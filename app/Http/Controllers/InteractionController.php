<?php

namespace App\Http\Controllers;

use App\Http\Resources\InteractionResource;
use App\Http\Resources\PostResource;
use App\Models\Interaction;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InteractionController extends Controller
{
    /* create Interactions */
    public function create(Request $request, $id)
    {
        $request->validate(['type' => ['required', Rule::in(['like', 'love'])]]);
        $post = Post::find($id);
        if ($post) {
            $interaction = Interaction::where('type', $request->type)->where('user_id', auth()->user()->id)->where('post_id', $id)->first();
            if ($interaction) {
                return response()->json(['status' => false, 'message' => 'Interaction already exist', 'Interaction' => new InteractionResource($interaction)], 400);
            } else {
                $interaction = Interaction::create(['type' => $request->type, 'user_id' => auth()->user()->id, 'post_id' => $id]);
                return response()->json(['status' => true, 'message' => 'Interaction created', 'Interaction' => new InteractionResource($interaction)], 201);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'post  Not Found'], 402);
        }
    }


    /* Who can delete the Interactions is the one who created it only */

    public function delete(Request $request, $id)
    {
        $interaction = Interaction::find($id);
        if ($interaction) {
            $interaction->delete();
            return response()->json(['status' => true, 'message' => 'interaction delete successfully'], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'interaction Not Found'], 402);
        }
    }

    /* View all the Interaction of one of the posts */

    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        if ($post) {
            $interactions = Interaction::where('post_id', $id)->get();
            return response()->json(['status' => true, 'post' => new PostResource($post),
                'interactions' => InteractionResource::collection($interactions)], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'post Not found'], 402);
        }
    }


}
