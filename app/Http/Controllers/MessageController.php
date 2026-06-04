<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        return $conversation
            ->messages()
            ->with('user')
            ->oldest()
            ->get();
    }
    public function store(Request $request, Conversation $conversation)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'conversation_id' => $conversation->id,
            'content' => $request->content
        ]);

        $conversation->update([
            'last_message_at' => now()
        ]);

        return response()->json(
            $message->load('user')
        );
    }
}
