<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserhResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'Date of birth'=>$this->birth,
            'bio'=>$this->bio,
            'Image'=>$this->getFirstMediaUrl('usersImages'),
        ];
    }
}
