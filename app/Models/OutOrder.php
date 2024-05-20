<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "out_orders";
    protected $fillable = [
        'order_id',
        'created_by',
    ];
    protected $appends = ['loted'];

    public function getLotedAttribute()
    {
        foreach ($this->products as $key) {
            $key->lotes = Lot::find($key->pivot->lote_id);
        }
        return true;
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_x_out_order')->withPivot(['quantity', 'lote_id', 'recipe_id']);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'products_x_out_order')->withPivot(['quantity']);
    }
}
