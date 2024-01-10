<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LoanPayment extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'user_id', 'amount', 'date',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getDateLabelAttribute(){
        return Carbon::createFromFormat("Y-m-d",$this->date)
            ->format('d/M/Y');
    }
}
