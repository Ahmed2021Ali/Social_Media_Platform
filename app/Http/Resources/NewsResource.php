<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            /* User Has Created posts */
            PostResource::collection(auth()->user()->posts),

            /* User Has shared posts */
            SharePostResource::collection(auth()->user()->sharePosts),

            /* friends Has created posts */
            PostResource::collection(auth()->user()->id != $this->sender_id  ? $this->sender->posts : $this->receiver->posts),

            /* friends Has shared posts */
            SharePostResource::collection(auth()->user()->id != $this->sender_id  ? $this->sender->sharePosts : $this->receiver->sharePosts)
        ];
    }
}
