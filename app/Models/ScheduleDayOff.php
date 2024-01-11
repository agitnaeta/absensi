<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDayOff extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable=[
      'schedule_id',
      'day'
    ];

    public function schedule(){
        return $this->hasMany(Schedule::class,'schedule_id','id');
    }

    public function days(){
        return $this->hasOne(Day::class,'id','day');
    }
}
