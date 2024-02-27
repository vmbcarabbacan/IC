<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'car_make_id',
        'name'
    ];
}
