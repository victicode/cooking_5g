<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receipet()
    {
        return $this->belongsTo(User::class, 'recept_id');
    }
    public function messages(){
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id')->orderBy('created_at', 'desc');
    }
}
