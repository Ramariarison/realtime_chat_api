<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar'
    ];

    protected $hidden = [
        'password',
    ];

    // JWT : identifiant de l'utilisateur
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT : données supplémentaires du token
    public function getJWTCustomClaims()
    {
        return [];
    }

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