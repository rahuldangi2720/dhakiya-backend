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
        $chat = Chat::create([
            'type' => 'private'
        ]);

        $chat->users()->attach([
            $currentUserId,
            $request->user_id
        ]);
        return response()->json([
            'message' => 'chat created successfully'
        ]);
    }
}
