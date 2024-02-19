<?php

namespace App\Interfaces;

interface CustomerInterface {

    public function getCustomer(array $condition, array $with = []);
    public function createOrUpdate(array $data);
}