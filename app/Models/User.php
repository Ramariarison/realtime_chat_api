<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    // Messages envoyés
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Conversations (Many-to-Many)
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    // Amis (relation principale)
    public function friends()
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'user_id',
            'friend_id'
        )->withPivot('status')
         ->withTimestamps();
    }
}
