<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use function PHPUnit\Framework\isEmpty;

class ChatController extends Controller
{
    /* Send Message for chat */
    public function sendMessage(SendMessageRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($request->message || $request->files) {
                $message = chat::create(['message' => $request->message, 'sender_id' => auth()->user()->id, 'receiver_id' => $id]);
                if ($request->files) {
                    foreach ($request->files as $file) {
                        foreach ($file as $f) {
                            $message->addMedia($f)->toMediaCollection('chatFiles');
                        }
                    }
                }
                event(new MessageSent($message->message));
                return response()->json(['message' => 'Message  created Successfully', 'messages' => new ChatResource($message)], 201);
            } else {
                return response()->json(['status' => false, 'message' => 'Message is required'],400);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'User not Found'], 402);
        }
    }

    /* Show All Messages for chat */
    public function showMessage($id)
    {
        $chats = Chat::where(function ($q) use ($id) {
            $q->where('sender_id', auth()->user()->id)->where('receiver_id', $id);
        })->Orwhere('receiver_id', auth()->user()->id)->where('sender_id', $id)->orderBy('id', 'desc')->get();
        if (!$chats->isEmpty()) {
            return response()->json(['status' => true, 'messages' => ChatResource::collection($chats)], 200);
        } else {
            return response()->json(['status' => true, 'message' => 'No  Messages ']);
        }
    }


    /*  You can delete the message because you only sent it  */
    public function removeMessage($id)
    {
        $message = Chat::find($id);
        if ($message) {
            $message->delete();
            return response()->json(['status'=>true,'message' => 'Message deleted Successfully','messages' => 'Message delete successfully '], 201);
        } else {
            return response()->json(['status'=>false,'message' => 'Message Not Found '], 402);
        }
    }

}
