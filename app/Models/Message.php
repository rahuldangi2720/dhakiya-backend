<?php

namespace App\Models;
use App\Models\Chat;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function chat(){
        return $this->belongsToMany(Chat::class);
    }

    public function sender(){
        return $this->belongsToMany(User::class , 'sender_id');
    }
}
