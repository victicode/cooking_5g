<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['status','trancker', 'other_address', 'created_by', 'client_id'];
    protected $table = "orders";

    
    protected $statuses = array(
        0   =>  [
            'status' => 'Cancelado',
            'svg'    => 'http://victorriverodev.infinityfreeapp.com/images/status/cancel.svg',
        ],
        1   =>  [
            'status' => 'Pendiente',
            'svg'    => 'http://victorriverodev.infinityfreeapp.com/images/status/pending.svg',
        ],
        2   =>  [
            'status' => 'En transito',
            'svg'    => 'http://victorriverodev.infinityfreeapp.com/images/status/in_transit.svg',
        ],
        3   =>  [
            'status' => 'Entregado', 
            'svg'    => 'http://victorriverodev.infinityfreeapp.com/images/status/confirm.svg',
        ],
    );
    // protected $appends = ['status_label', 'lote'];
    protected $appends = ['status_label'];
    
    public function getStatusLabelAttribute()
    {
      return  $this->statuses[$this->status];  
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }
    public function outOrder(){
        return $this->hasOne(OutOrder::class, 'order_id', 'id');
    }
    public function lastNotifiaction(){
        return $this->hasMany(Order::class, 'client_id', 'id')->orderBy('created_at', 'ASC')->first();
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_x_orders')->withPivot(['quantity','recipe_id']);
    }
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'products_x_orders')->withPivot(['quantity'])->withTrashed();
    }
    

}
