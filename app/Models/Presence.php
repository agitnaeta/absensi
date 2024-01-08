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
        'user_id', 'in', 'out', 'overtime_in', 'overtime_out', 'is_overtime', 'no_record',
    ];
}
