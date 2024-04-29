<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;


    protected $table = 'messages_x_chat';
    protected $fillable = ['message', 'chat_id', 'type_messages', 'sender_id', 'read'];
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
