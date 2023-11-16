<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['status','trancker','created_by'];
    protected $table = "orders";
    protected $statuses = array(
        0   =>  'Cancelado',
        1   =>  'Pendiente',
        2   =>  'En transito',
        3   =>  'Entregado en almacen',
    );

    public function getStatusLabelAttribute()
    {
        $this->status_info = $this->statuses[$this->status];  
    }

    public function getStatusByKey($key)
    {
        return $this->statuses[$key];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_x_orders');
    }
}
