<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    /* Send Message for chat */
    public function sendMessage(SendMessageRequest $request, User $user)
    {
        if ($user->id === auth()->user()->id) {
            return response()->json(['message' => "You cannot send message to yourself"]);
        }
        if ($request->message || $request->files) {
            $collection = IsTherePreviousChat($user->id, auth()->user()->id);
            if ($collection === false) {
                $chat = Chat::create(['sender_id' => auth()->user()->id, 'receiver_id' => $user->id]);
            }
            $message = Message::create(['sender_id' => auth()->user()->id, 'receiver_id' => $user->id,
                'content' => $request->message, 'chat_id' => $collection === false ? $chat->id : $collection[0]->chat_id
            ]);
            uploadFiles($request['files'], $message,'chatFiles');
            broadcast(new MessageSent($message->toArray(), $message->receiver_id))->toOthers();
            return response()->json(['message' => 'Message  created Successfully', 'messages' => new ChatResource($message)], 201);;
        } else {
            return response()->json(['status' => false, 'message' => 'Message is required'], 400);
        }

    }

    /* Show All chat by id */

    public function showChat(Chat $chat)
    {
       // $chats = Message::where('chat_id', $chat->id)->get();
        return response()->json(['status' => true, 'messages' => ChatResource::collection($chat->messages)], 200);
    }

    /*  You can delete the message because you only sent it  */
    public function removeMessage(Message $message)
    {
            $message->delete();
            return response()->json(['status' => true, 'messages' => 'Message delete successfully '], 201);
    }

}
