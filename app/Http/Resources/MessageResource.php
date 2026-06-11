<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'chat_id'=>$this->id,
            'message'=>$this->message,
            'is_read'=>$this->is_read,
            'sender'=>[
                'id'=> $this->sender->id,
                'name'=>$this->sender->name,
                'email'=>$this->sender->email,
            ],
            'created_at'=>$this->created_at,
        ];
    }
}
