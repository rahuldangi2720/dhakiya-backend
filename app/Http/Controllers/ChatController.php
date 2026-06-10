<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        $currentUserId = auth('api')->id();

        if ($currentUserId == $request->user_id) {
            return response()->json([
                'message' => 'You cannot chat with yourself'
            ], 400);
        }

        $existingChat = Chat::whereHas('users', function ($query) use ($currentUserId) {

            $query->where('users.id',$currentUserId);

        })
            ->whereHas('users', function ($query) use ($request) {

                $query->where('users.id',$request->user_id);
            })
            ->first();
            if ($existingChat){
                return response()->json([
                    'message'=>'chat already exists',
                    'chat'=>$existingChat
                ]);
            }


        $chat = Chat::create([
            'type' => 'private'
        ]);

        $chat->users()->attach([
            $currentUserId,
            $request->user_id
        ]);

        
        return response()->json([
            'message' => 'chat created successfully',
            'chat' => $chat
        ]);

        
    }
}
