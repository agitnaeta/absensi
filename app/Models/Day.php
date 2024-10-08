<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable =['name'];

    public function dayOff(){
        return $this->hasMany(ScheduleDayOff::class,'day','id');
    }
}
