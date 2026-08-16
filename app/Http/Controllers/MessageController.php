<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
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
        $message_content = $request->content_msg;

        $message = Message::create([
            'user_id' => auth()->id(),
            'conversation_id' => $conversation->id,
            'content' => $message_content
        ]);

        $message->load('user');

        $conversation->update([
            'last_message_at' => now()
        ]);

        MessageSent::dispatch($conversation, $message);

        return response()->json([
           'message' => $message,
           'status' => 'Message envoyé'
        ], 201);
    }
}
