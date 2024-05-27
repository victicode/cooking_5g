<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'chats';
    protected $fillable = ['title', 'type', 'status', 'ticket_number', 'reference_id', 'sender_id', 'recept_id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withTrashed();
    }
    public function receipet(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recept_id')->withTrashed();
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
