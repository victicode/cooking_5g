<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model
{
    use HasFactory, SoftDeletes;
    // use HasFactory;
    const CREATED_AT = 'created_at_lote';
    const UPDATED_AT = 'updated_at_lote';
    const DELETED_AT = 'deleted_at_lote';
    protected $primaryKey = 'id_lote';
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
