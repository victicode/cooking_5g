<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    protected $appends = ['total_stock', 'total_products_in_order'];
    
    public function getTotalStockAttribute()
    {
        $stockInLotes= 0;
        foreach ($this->lotes as $lote) {

            try {
                if(strtotime($lote->due_date) > strtotime(date('Y-m-d')) ){
                    
                    $stockInLotes = $stockInLotes +  floatval($lote->quantity);
                }
            } catch ( Exception $r) {
                $stockInLotes = $r->getMessage();
            }
        }
        $quintityInOrders = 0;
        foreach ($this->recipes as $recipe) {
            try {
                foreach($recipe->orders as $order) {
                    if($order->status == 1){

                        try {
                            $quintityInOrders = $quintityInOrders +  ( floatval($order->pivot->quantity) * floatval($recipe->pivot->quantity));
                        } catch ( Exception $r) {
                            $quintityInOrders = $r->getMessage();
                        }
                    }
                }
            }catch ( Exception $r) {
                $stockInLotes = $r->getMessage();
            }
        }

      return floatval( $stockInLotes) - floatval($quintityInOrders);  
    }
    public function getTotalProductsInOrderAttribute()
    {
        $l= 0;
        foreach ($this->orders as $order) {
            try {
                //code...
                $l = $l +  floatval($order->pivot->quantity);
            } catch ( Exception $r) {
                //throw $th;
                $l = $r->getMessage();
            }
        }
        return $l ;
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'products_x_orders')->withPivot(['quantity']);
    }
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'products_x_recipes')->withPivot(['quantity']);
    }
    public function out_orders()
    {
        return $this->belongsToMany(OutOrder::class, 'products_x_out_order');
    }
    public function dismantling(){
        return $this->hasMany(Dismantling::class, 'product_id', 'id');
    }
    public function notification(){
        return $this->hasMany(Notification::class, 'product_id', 'id');
    }
    public function lotes(){
        return $this->hasMany(Lot::class, 'product_id', 'id')->orderBy('due_date', 'ASC');
    }
    public function lotesRecipe(){
        return $this->hasMany(Lot::class, 'product_id', 'id')
            ->where('quantity', '>', 0)
                ->whereDate('due_date', '>' , date('Y-m-d'))
                    ->orderBy('due_date', 'ASC');
    }
}
