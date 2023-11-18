<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'stock',
        'img',
        'is_dismantling',
        'created_by',
        'updated_by', 
    ];


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'products_x_orders');
    }
}
