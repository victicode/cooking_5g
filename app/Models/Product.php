<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "products";
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'stock',
        'type_of_unit',
        'img',
        'is_dismantling',
        'created_by',
        'updated_by', 
        'due_date_most_evenly'
    ];
    protected $appends = ['stock_total'];

    public function getstockTotalAttribute()
    {
        $total = 0;
        foreach ($this->lotes as $lote) {
            $total = $total + $lote->quantity;
        }
        return $total;
        
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'products_x_orders');
    }
    public function out_orders()
    {
        return $this->belongsToMany(OutOrder::class, 'products_x_out_order');
    }
    public function dismantling(){
        return $this->hasMany(Dismantling::class, 'product_id', 'id');
    }
    public function lotes(){
        return $this->hasMany(Lot::class, 'product_id', 'id')->orderBy('due_date', 'ASC')->where('quantity', '>', 0);
    }
}
