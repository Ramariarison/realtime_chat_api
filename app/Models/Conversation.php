<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_message_at'
    ];

    // Participants
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
