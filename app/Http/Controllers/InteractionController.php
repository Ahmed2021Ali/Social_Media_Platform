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
    public function create(Request $request, Post $post)
    {
        $request->validate(['type' => ['required', Rule::in(['like', 'love'])]]);
        $interaction = Interaction::where('type', $request->type)->where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
        if ($interaction) {
            return response()->json(['status' => false, 'message' => 'Interaction already exist', 'Interaction' => new InteractionResource($interaction)], 400);
        } else {
            $interaction = Interaction::create(['type' => $request->type, 'user_id' => auth()->user()->id, 'post_id' => $post->id]);
            return response()->json(['status' => true, 'message' => 'Interaction created', 'Interaction' => new InteractionResource($interaction)], 201);
        }
    }


    /* Who can delete the Interactions is the one who created it only */

    public function delete(Interaction $interaction)
    {
        $interaction->delete();
        return response()->json(['status' => true, 'message' => 'interaction delete successfully'], 201);
    }

    /* View all the Interaction of one of the posts */

    public function show(Post $post)
    {
        return response()->json(['status' => true, 'post' => new PostResource($post),
            'interactions' => InteractionResource::collection($post->interactions)], 200);
    }


}
