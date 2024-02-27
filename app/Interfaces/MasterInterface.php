<?php

namespace App\Interfaces;

interface MasterInterface {

    public function addOrUpdateMake(array $data);
    public function addOrUpdateModel(array $data);
    public function addOrUpdateYear(array $data);
}