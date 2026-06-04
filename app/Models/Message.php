<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'conversation_id',
        'content',
        'is_read'
    ];

    // Expéditeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}