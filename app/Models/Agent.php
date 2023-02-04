<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $with = ['company'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getFirstNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getMiddleNameAttribute($value)
    {
        return ucwords($value);
    }

}
