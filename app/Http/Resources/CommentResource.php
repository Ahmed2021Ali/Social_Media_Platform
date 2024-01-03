<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'post' => $this->post->description,
            'title' => $this->title,
            'files'=>ImagesResource::collection($this->getMedia('commentFiles')),

        ];
    }
}
