<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['message', 'product_id', 'sender_id', 'recept_id', 'read'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function recept()
    {
        return $this->belongsTo(User::class, 'recept_id');
    }
}
