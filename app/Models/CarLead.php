<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_detail_id',
        'car_driver_detail_id',
        'status',
        'session_id',
        'btm_source',
        'utm_source',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_term'
    ];

    public function driver() {
        return $this->hasOne(CarDriverDetail::class, 'id', 'car_driver_detail_id');
    }
}
