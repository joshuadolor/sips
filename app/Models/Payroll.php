<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'employee_code',
        'employee_name',
        'company_id',
        'date_from',
        'date_until',
        'hours',
        'rate',
        'deduction',
    ];

    protected $appends = ['total'];

    protected $with = ['employee', 'createdBy'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getTotalAttribute()
    {
        return ($this->hours * $this->rate) - $this->deductions;
    }
}
