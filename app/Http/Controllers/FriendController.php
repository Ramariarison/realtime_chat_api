<?php

namespace App\Http\Controllers;

use App\Models\User as ModelUser;
use App\Models\Friend;

class FriendController extends Controller
{
    public function invite(ModelUser $user)
    {
        $currentUser = auth()->user();

        Friend::create([
            'user_id' => $currentUser->id,
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Invitation envoyée'
        ]);
    }

    public function requests()
    {
        $requests = Friend::with('user')
            ->where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json(
            $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'user_id' => $request->user->id,
                    'name' => $request->user->name,
                    'email' => $request->user->email,
                    'avatar' => $request->user->avatar,
                    'created_at' => $request->created_at,
                ];
            })
        );
    }

    public function accept(ModelUser $friend)
    {
        $request = Friend::where('user_id', $friend->id)
            ->where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update([
            'status' => 'accepted'
        ]);

        return response()->json([
            'message' => 'Invitation acceptée'
        ]);
    }

    public function decline(ModelUser $friend)
    {
        $request = Friend::where('user_id', $friend->id)
            ->where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->delete();

        return response()->json([
            'message' => 'Invitation refusée'
        ]);
    }
}
