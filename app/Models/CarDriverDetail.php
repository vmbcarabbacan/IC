<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDriverDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_name',
        'dob',
        'nationality',
        'first_driving_country',
        'car_first_registration_date',
        'driving_experience',
        'expected_policy_start_date',
        'is_personal_use',
        'is_policy_active',
        'is_fully_comprehensive',
        'is_new',
        'car_year',
        'brand_id',
        'model_id',
        'trim_level_id',
        'car_value',
        'country'
    ];
}
