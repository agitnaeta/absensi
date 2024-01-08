<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'user_id', 'amount', 'date',
    ];
}
