<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'user_id', 'amount', 'overtime_amount', 'overtime_type',
    ];
}
