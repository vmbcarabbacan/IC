<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'agent_id',
        'insurance_type',
        'status',
        'session_id',
        'btm_source',
        'utm_source',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_term'
    ];
}
