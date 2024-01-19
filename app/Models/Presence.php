<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'in',
        'out',
        'user_id',
        'is_overtime',
        'is_late',
        'late_minute',
        'lat',
        'lng',
        'outside',
    ];

    protected $guarded =[];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
