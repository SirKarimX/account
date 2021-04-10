<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $table = 'stock_transactions';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true;

    protected $dates = [
      'dated',
      'created_at',
    ];

    protected $casts = [
        'quantity' => 'float',
        'line_total' => 'float',
    ];

    protected $fillable = [
        'dated',
        'warehouse_id',
        'item_id',
        'reference_id',
        'reference_line_id',
        'referee_id',
        'reference_type',
        'quantity',
        'line_total',
        'description',
        'created_at',
    ];

    public function item()
    {
        return $this->hasOne(ProductService::class, 'id', 'item_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'reference_id');
    }

    public function bill()
    {
        return $this->hasOne(Bill::class, 'id', 'reference_id');
    }


}
