<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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
        'company_id',
        'employee_code',
    ];

    protected $with = ['company'];
    protected $appends = ['full_name'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getFullNameAttribute()
    {
        return "$this->last_name, $this->first_name $this->middle_name";
    }

}
