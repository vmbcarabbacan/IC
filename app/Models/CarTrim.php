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
        'model_body',
        'model_engine_position',
        'model_engine_cc',
        'model_engine_cyl',
        'model_engine_type',
        'model_engine_valves_per_cyl',
        'model_engine_power_ps',
        'model_engine_power_rpm',
        'model_engine_torque_nm',
        'model_engine_torque_rpm',
        'model_engine_bore_mm',
        'model_engine_stroke_mm',
        'model_engine_compression',
        'model_engine_fuel',
        'model_top_speed_kph',
        'model_0_to_100_kph',
        'model_drive',
        'model_transmission_type',
        'model_seats',
        'model_doors',
        'model_weight_kg',
        'model_length_mm',
        'model_width_mm',
        'model_height_mm',
        'model_wheelbase_mm',
        'model_lkm_hwy',
        'model_lkm_mixed',
        'model_lkm_city',
        'model_fuel_cap_l',
        'model_co2',
        'make_country'
    ];
}
