<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_expires',
        'refresh_expires',
        'is_active'
    ];
}
