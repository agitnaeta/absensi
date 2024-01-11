<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecap extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'user_id', 'recap_month', 'work_day', 'late_day', 'salary_amount', 'overtime_amount',
        'loan_cut', 'late_cut', 'abstain_cut', 'received',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
