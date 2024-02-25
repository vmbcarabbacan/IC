<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class Device extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'country_product_id',
        'device_type',
        'device_uuid',
        'source_id',
        'utm_campaign',
        'utm_medium',
        'utm_source',
        'utm_term'
    ];
}
