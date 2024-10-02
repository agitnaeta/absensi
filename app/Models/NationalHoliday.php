<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalHoliday extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable =['date','info',"company_id"];
}
