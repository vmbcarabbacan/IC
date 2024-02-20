<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'team_leader_id',
        'underwriter_id',
        'is_underwriter',
        'is_round_robin',
        'agent_type',
        'renewal_deals',
        'insurance_type',
        'failed_attempt',
        'status',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
