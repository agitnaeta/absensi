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
        'loan_cut', 'late_cut', 'abstain_cut', 'received','abstain_count','desc','paid','method'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getPaidStatusAttribute(){
        $paid = $this->paid ? "Ya|$this->method" :"Belum";
        return $paid;
    }
}

