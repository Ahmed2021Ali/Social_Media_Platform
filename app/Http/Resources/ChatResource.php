<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'content' => $this->content,
            'chat_id' => $this->chat_id,
            'files'=>ImagesResource::collection($this->getMedia('chatFiles')),
            'sender Message' => $this->sender->name,
            'receiver Message'=> $this->receiver->name,
        ];
    }
}
