<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request){
        $request->validate([
          'chat_id'=>'required | exists: chats , id',
          'message'=> 'required | string|max:5000'
        ]);

        $sender_id = auth('api')->id();

        $message =  Message::create([
            'chat_id'=> $request->chat_id,
            'message'=> $request->message,
            'sender_id'=> auth('api')->id()
        ]);


    }
}
