<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string|max:5000'
        ]);   // vallidate request what will request send in request  
        $sender_id = auth('api')->id();  // crunnent user


        $chat = Chat::find($request->chat_id); // chat table me current chat id ko find karo
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not Found '
            ], 404);
        }   // if chat not found then it will give error

        $isMember = $chat->users()->where('users.id', $sender_id)->exists();  // after find chat id in chat table find current user is member of chat table or not

        if (!$isMember) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        } // if member then no error if member not then error will show unauthorized

        $message =  Message::create([
            'chat_id' => $request->chat_id,
            'message' => $request->message,
            'sender_id' => $sender_id
        ]);   // then create message |save message

        $message->load('sender');
        return response()->json([
            'message' => 'message sent successfully',
            'data' => new MessageResource($message)
        ], 201);
        // return message when message sent successfuly
    }



    public function getMessages(Request $request, $chatId)
    {
        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not found'
            ], 404);
        }

        $currentUserId =  auth('api')->id();

        $isMember = $chat->users()->where('users.id', $currentUserId)->exists();
        if (!$isMember) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $messages = $chat->messages()->where('sender')->latest()->paginate(20);  // get all message from messages table

        return response()->json([
            'message' => 'Messages fetched successfully',
            'data' =>  MessageResource::collection($messages)
        ]);
    }

    public function markAsRead(Request $request, $chatId)
    {

        $chat = Chat::find($chatId);
        if (!$chat) {
            return response()->json([
                'message' => 'Chat not found'
            ], 404);
        }

        $currentUserId = auth('api')->id();
        $isMember = $chat->users()->where('users.id', $currentUserId)->exists();
        if (!$isMember) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

       $updatedCount =  $chat->messages()
            ->where('sender_id', '!=', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Messages marked as read successfully',
            'updated_count'=> $updatedCount
        ]);
    }
}
