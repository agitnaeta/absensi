<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acc extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable =[
      'code',
      'destination_id',
      'source_id',
      'source_name',
      'destination_name'
    ];
}
