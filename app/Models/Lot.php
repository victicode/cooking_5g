<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "lotes";
    protected $fillable = [
        'product_id',
        'lote_code',
        'quantity',
        'due_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
