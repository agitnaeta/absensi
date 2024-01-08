<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable =[
        'name',
        'in',
        'out',
        'over_in',
        'over_out',
        'off_day_per_month',
        'fine_per_minute',
        'day_off'
    ];

    public function user()
    {
        return $this->hasMany(User::class,'schedule_id','id');
    }
}
