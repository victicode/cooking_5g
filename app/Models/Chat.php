<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = ['title', 'type', 'status', 'reference_id', 'sender_id', 'recept_id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receipet(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recept_id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id')->orderBy('created_at', 'desc');
    }
    public function messagesUnread(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id')->where('read', 0)->orderBy('created_at', 'desc');
    }
}
