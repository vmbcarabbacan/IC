<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarLeadTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'car_lead_id',
        'agent_id',
        'closed_at',
        'due_date',
        'customer_status_id',
        'lead_status_id',
        'disposition_id',
        'lost_lead_reason_id',
        'task_notes',
        'is_renewal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
