<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Resources\FriendsResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\SearchResource;
use App\Http\Resources\UserhResource;
use App\Models\FriendRequest;
use App\Models\User;


class UserController extends Controller
{

    /*  show user by ID */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(['status' => true, 'User' => new UserhResource($user)], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'User Not Found.'], 404);
        }
    }

    /*  show Current User */
    public function showCurrentUser()
    {
        return response()->json(['status' => true, 'User' => new UserhResource(auth()->user())], 200);
    }

    /*  show Friends of user by User_ID */
    public function friends()
    {
        $friends = $this->getFriends();
        if ($friends) {
            return response()->json(['status' => true, 'Friends' => FriendsResource::collection($friends)], 200);
        } else {
            return response()->json(['status' => false, 'message' => ' No Friends for You'], 404);
        }
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
        return response()->json(['posts' => NewsResource::collection($this->getFriends()),], 200);
    }

    public function getFriends()
    {
        return FriendRequest::where(function ($q) {
            $q->where('sender_id', auth()->user()->id)
                ->orwhere('receiver_id', auth()->user()->id);
        })->where('status', 'accepted')->get();
    }

}
