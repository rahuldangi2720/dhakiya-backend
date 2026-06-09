<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Chat extends Model
{
   public function users(){
    return $this->belongsToMany(User::class);
   }
   public function messages(){
    return $this->hasMany(Message::class);
   }
}
