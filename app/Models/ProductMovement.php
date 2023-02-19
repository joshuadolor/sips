<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'type',
        'cost',
        'agent_id',
        'product_id',
        'user_id',
    ];

    protected $with = ['product', 'agent'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Agent', 'agent_id');
    }

}
