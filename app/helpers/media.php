<?php

use App\Models\FriendRequest;
use App\Models\Message;


function getFriends()
{
    return FriendRequest::where(function ($q) {
        $q->where('sender_id', auth()->user()->id)
            ->orwhere('receiver_id', auth()->user()->id);
    })->where('status', 'accepted')->get();
}

function uploadFiles($files, $model, $folder)
{
    if ($files) {
        foreach ($files as $file) {
            $model->addMedia($file)->toMediaCollection($folder);
        }
    }
}

function IsTherePreviousChat($OtherUserId, $user_id)
{
    $collection = Message::whereHas('chat', function ($q) use ($OtherUserId, $user_id) {
        $q->where('sender_id', $OtherUserId)
            ->where('receiver_id', $user_id);
    })->orWhere(function ($q) use ($OtherUserId, $user_id) {
        $q->where('sender_id', $user_id)
            ->where('receiver_id', $OtherUserId);
    })->get();
    if (count($collection) > 0) {
        return $collection;
    }
    return false;
}
