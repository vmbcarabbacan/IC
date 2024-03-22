<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GlobalService;

class RecentSearches extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function getDeviceIdAttribute($value) {
        $global = new GlobalService();
        
        return $global->encrypt($value);
    }
}
