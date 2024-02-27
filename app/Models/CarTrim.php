<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTrim extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'car_make_id',
        'car_model_id',
        'car_year',
        'cylinders',
        'no_seats',
        'is_vintage',
        'new_min',
        'new_max',
        'old_min',
        'old_max'
    ];
}
