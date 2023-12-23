<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use function PHPUnit\Framework\isEmpty;

class ChatController extends Controller
{

    public function sendMessage(SendMessageRequest $request, $id)
    {
        if ($request->message || $request->file) {
            $chat = chat::create([
                'message'=> $request->message,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $id
            ]);
            if ($request->file) {
                $chat->addMediaFromRequest('file')->toMediaCollection('chatImages');
            }
            //  $chat->addMedia($file)->toMediaCollection('usersImages');
            return new ChatResource($chat);
        } else {
            return response()->json(['status' => false, 'message' => 'Message is required'], 404);
        }
    }


    public function showMessage($id)
    {
        $chats = Chat::where(function ($q) use ($id) {
            $q->where('sender_id', auth()->user()->id)->where('receiver_id', $id);
        })->Orwhere('receiver_id', auth()->user()->id)->where('sender_id', $id)->orderBy('id', 'desc')->get();
        if (!$chats->isEmpty()) {
            return ChatResource::collection($chats);
        } else {
            return response()->json(['status' => true, 'message' => 'No  Messages '], 404);
        }
    }

    /*  You can delete the message because you only sent it  */
    public function removeMessage($id)
    {
        $message = Chat::where('id', $id)->where('sender_id', auth()->user()->id)->first();
        if ($message) {
            $message->delete();
            return response()->json(['message' => 'Message delete successfully '], 200);
        } else {
            return response()->json(['message' => 'Message Not Found '], 404);
        }
    }

}
