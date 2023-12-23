<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendUserRequest;
use App\Http\Resources\FriendsResource;
use App\Models\FriendRequest;
use App\Models\User;

class FriendRequestController extends Controller
{

    public function sendRequest($id)
    {
        $user = User::find($id);
        if ($user) {
            $friendRequest = FriendRequest::where('sender_id', auth()->user()->id)->where('receiver_id', $user->id)->first();
            $friendRequestReceived = FriendRequest::where('sender_id', $user->id)->where('receiver_id', auth()->user()->id)->first();
            if ($friendRequest) {
                return response()->json(['status' => false, 'message' => 'You have already sent a friend request to ' . $friendRequest->receiver->name], 200);
            } elseif ($friendRequestReceived) {
                return response()->json(['status' => false, 'message' => $friendRequestReceived->sender->name . ' has send you friend request already '], 200);
            } else {
                FriendRequest::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $user->id,
                ]);
                return response()->json(['status' => true, 'message' => 'You Send Friend Request to ' . $user->name,], 200);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Receiver Not Friend '], 404);
        }
    }

    public function removeRequest($id)
    {
        $friendRequest = FriendRequest::where('sender_id', auth()->user()->id)->where('receiver_id', $id)->first();
        if ($friendRequest) {
            $friendRequest->delete();
            return response()->json(['status' => true, 'message' => 'your Friend Request Remove to ' . $friendRequest->receiver->name,], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'your Friendly Request already Removed or Request not exist '], 404);
        }
    }

    public function acceptRequest($id)
    {
        $friendRequestReceived = FriendRequest::where('sender_id', $id)->where('receiver_id', auth()->user()->id)->first();
        if ($friendRequestReceived) {
            if ($friendRequestReceived->status === 'rejected') {
                return response()->json(['status' => false, 'message' => 'your dont Accept Friendly Request  after Rejected to  ' . $friendRequestReceived->sender->name], 200);
            } elseif ($friendRequestReceived->status === 'accepted') {
                return response()->json(['status' => false, 'message' => 'your Friendly Request already Accepted  to  ' . $friendRequestReceived->sender->name], 200);
            } else {
                $friendRequestReceived->update(['status' => 'accepted']);
                return response()->json(['status' => true, 'message' => 'your accepted Friendly Request  to  ' . $friendRequestReceived->sender->name], 200);
            }
        }else {
            return response()->json(['status' => false, 'message' => 'Friendly Request  Not found  ' ], 404);
        }

    }

    public function rejectRequest($sender_id)
    {
        $friendRequest = FriendRequest::where('sender_id', $sender_id)->where('receiver_id', auth()->user()->id)->first();
        if ($friendRequest) {
            if ($friendRequest->status === 'accepted' || $friendRequest->status === null) {
                $friendRequest->update(['status' => 'rejected']);
                return response()->json(['status' => true, 'message' => 'You removed Friend Request to ' . $friendRequest->sender->name], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'You removed Friend Request already from ' . $friendRequest->sender->name], 404);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Friend Request Not Found '], 404);
        }
    }


    public function friends()
    {
        $sentFriendRequests = FriendRequest::where('receiver_id', auth()->user()->id)->where('status', null)->get();
        if (!$sentFriendRequests->isEmpty()) {
            return FriendsResource::collection($sentFriendRequests);
        } else {
            return response()->json(['message' => 'No  Sent Friend Requests '], 404);
        }
    }
}