<?php

namespace App\Interfaces;

interface UserInterface {

    public function getUser(array $condition, array $with = []);
    public function saveUser(array $data);
    public function updateUser(array $data);
}