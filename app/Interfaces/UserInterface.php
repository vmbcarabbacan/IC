<?php

namespace App\Interfaces;

interface UserInterface {

    public function getUser(array $condition, array $with = []);
}