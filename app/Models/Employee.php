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

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

}
