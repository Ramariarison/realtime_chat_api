<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function start(User $user)
    {
        $authUser = auth()->user();

        $conversation = Conversation::whereHas(
            'users',
            fn($q) => $q->where('users.id', $authUser->id)
        )
        ->whereHas(
            'users',
            fn($q) => $q->where('users.id', $user->id)
        )
        ->first();

        if (!$conversation) {

            $conversation = Conversation::create();

            $conversation->users()->attach([
                $authUser->id,
                $user->id
            ]);
        }

        return response()->json($conversation);
    }

    public function index()
    {
        $user = auth()->user();

        $conversations = $user
            ->conversations()
            ->with([
                'users',
                'messages'
            ])
            ->latest('last_message_at')
            ->get();

        return response()->json(
            $conversations->map(function ($conversation) use ($user) {

                $friend = $conversation
                    ->users
                    ->firstWhere(
                        'id',
                        '!=',
                        $user->id
                    );

                $lastMessage = $conversation
                    ->messages
                    ->sortByDesc('created_at')
                    ->first();

                return [
                    'id' => $conversation->id,
                    'name' => $friend->name,
                    'avatar' => $friend->avatar,
                    'message' => $lastMessage?->content ?? '',
                    'time' => optional(
                        $conversation->last_message_at
                    )->format('H:i'),
                    'unreadCount' => 0,
                    'isOnline' => false,
                ];
            })
        );
    }
}
