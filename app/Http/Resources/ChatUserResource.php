<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chat_id '=> $this->id,
            'name'=> $this->sender_id === auth()->user()->id ? $this->receiver->name : $this->sender->name,
           // 'picture'=> $this->sender_id === auth()->user()->id ? $this->receiver->file : $this->sender->file,

        ];
    }
}
