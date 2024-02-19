<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Services\GlobalService;

class UserRepository extends GlobalService implements UserInterface {


    public function getUser(array $condition, array $with = [])
    {
        return $this->model(User::class, $condition, $with);
    }

}