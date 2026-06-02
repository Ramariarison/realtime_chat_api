<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
