<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
class Order extends Model
{
    use HasFactory;
    protected $fillable = ['status','trancker','created_by'];
    protected $table = "orders";

    
    protected $statuses = array(
        0   =>  [
            'status' => 'Cancelado',
            'svg'    => 'http://10.10.10.69:8085/images/status/cancel.svg',
        ],
        1   =>  [
            'status' => 'Pendiente',
            'svg'    => 'http://10.10.10.69:8085/images/status/pending.svg',
        ],
        2   =>  [
            'status' => 'En transito',
            'svg'    => 'http://10.10.10.69:8085/images/status/in_transit.svg',
        ],
        3   =>  [
            'status' => 'Entregado', 
            'svg'    => 'http://10.10.10.69:8085/images/status/confirm.svg',
        ],
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
        return $this->belongsToMany(Product::class, 'products_x_orders')->withPivot('quantity');
    }
}
