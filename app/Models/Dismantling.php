<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dismantling extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "dismantling";
    protected $fillable = [
        'product_id',
        'piece_product_id',
        'quantity',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function products_pieces()
    {
        return $this->belongsTo(Product::class, 'piece_product_id');
    }


}
