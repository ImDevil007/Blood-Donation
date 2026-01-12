<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LovCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
