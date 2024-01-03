<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Resources\ChatUserResource;
use App\Http\Resources\FriendsResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\SearchResource;
use App\Http\Resources\UserhResource;
use App\Models\Chat;
use App\Models\FriendRequest;
use App\Models\User;


class UserController extends Controller
{

    /*  show user by ID */
    public function show(User $user)
    {
        return response()->json(['status' => true, 'User' => new UserhResource($user)], 200);
    }

    /*  show Current User */
    public function showCurrentUser()
    {
        return response()->json(['status' => true, 'User' => new UserhResource(auth()->user())], 200);
    }

    /*  show Friends of user by User_ID */
    public function friends()
    {
        return response()->json(['status' => true, 'Friends' => FriendsResource::collection(getFriends())], 200);
    }

    /* search for  user By Name */
    public function search(SearchUserRequest $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->search . '%')->get();
        if (!$users->isEmpty()) {
            return response()->json(['status' => true, 'Result of Search' => SearchResource::collection($users)], 200);
        } else {
            return response()->json(['message' => 'Not found Users'], 404);
        }
    }

    /* News Feed for  user */
    public function newsFeed()
    {
        return response()->json(['posts' => NewsResource::collection(getFriends()),], 200);
    }

    /* chat of users */
    public function chats()
    {
        $chats = Chat::where('sender_id', auth()->user()->id)->Orwhere('receiver_id', auth()->user()->id)->get();
        return response()->json(['users' => ChatUserResource::collection($chats)], 200);
    }


}
