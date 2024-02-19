<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LoggingTrait {

    /**
     * 1. Register your channel in logging.php
     * 2. Create folder in storage/logs/
     */

    public function logError($data) {
        Log::channel('errors')->error($data);
    }

    public function logInfo($data) {
        Log::channel('informations')->info($data);
    }
}