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
            'svg'    => 'http://192.168.211.91:8085/images/status/cancel.svg',
        ],
        1   =>  [
            'status' => 'Pendiente',
            'svg'    => 'http://192.168.211.91:8085/images/status/pending.svg',
        ],
        2   =>  [
            'status' => 'En transito',
            'svg'    => 'http://192.168.211.91:8085/images/status/in_transit.svg',
        ],
        3   =>  [
            'status' => 'Entregado', 
            'svg'    => 'http://192.168.211.91:8085/images/status/confirm.svg',
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
        return $this->belongsTo(User::class, 'created_by');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function outOrder(){
        return $this->hasOne(OutOrder::class, 'order_id', 'id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_x_orders');
    }
    

}
