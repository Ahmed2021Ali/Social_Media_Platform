<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           'your Friend' => $this->sender_id != auth()->user()->id ?  new UserhResource($this->sender) : new UserhResource($this->receiver)
        ];
    }
}
