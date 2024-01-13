<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable=[
        'name',
        'address',
        'phone',
        'image',
        'id_card'
    ];
}
