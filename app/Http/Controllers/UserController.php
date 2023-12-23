<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Resources\FriendsResource;
use App\Http\Resources\SearchResource;
use App\Http\Resources\UserhResource;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return new UserhResource($user);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User Not Found.',
            ], 404);
        }
    }

    public function friends(Request $request)
    {
        $friends = FriendRequest::where(function ($q) use ($request) {
            $q->where('sender_id', auth()->user()->id)
                ->orwhere('receiver_id', auth()->user()->id);
        })->where('status', 'accepted')->get();
        if ($friends) {
            return FriendsResource::collection($friends);
        } else {
            return response()->json([
                'status' => false,
                'message' => ' No Friends for You',
            ], 404);
        }
    }

    public function search(SearchUserRequest $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->search . '%')->get();
        if (!$users->isEmpty()) {
            return SearchResource::collection($users);
        } else {
            return response()->json([
                'message' => 'Not found Users',
            ], 404);
        }
    }

}
