<?php

namespace App\Repositories;

use App\Interfaces\WebsiteInterface;
use App\Models\Public\Device;
use App\Services\GlobalService;

class WebsiteRepository extends GlobalService implements WebsiteInterface {

    public function device(array $data)
    {
        if(!isset($data['device_uuid']))
            $data['device_uuid'] = $this->randomString(8) . '-'. $this->randomString(5) . '-'. $this->randomString(5) . '-'. $this->randomString(5) . '-'. $this->randomString(12);

        $device = Device::where('device_uuid', $data['device_uuid'])->first();

        if(!$device)
            $device = new Device();

        $device->fill($data);
        $device->save();

        $device['auth'] = $device->createToken('public')->accessToken;
        return $device;
    }
}