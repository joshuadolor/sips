<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $appends = ['agent', 'total_cost'];

    protected $fillable = [
        'company_id',
        'user_id',
        'transaction_date',
    ];

    public function productMovements()
    {
        return $this->hasMany('App\Models\ProductMovement', 'sale_id');
    }

    public function getProductsAttribute()
    {
        return $this->productMovements->pluck('product');
    }

    public function getAgentAttribute()
    {
        return $this->productMovements->pluck('agent')->first();
    }

    public function getTotalCostAttribute()
    {
        return $this->productMovements->pluck('cost')->sum();
    }
}
