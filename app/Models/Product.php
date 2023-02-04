<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'item_code',
        'quantity',
        'price',
        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $with = ['company'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

}
