<?php

namespace App\Interfaces;

interface UserInterface {

    public function getUser(array $condition, array $with = []);
    public function saveUser(array $data);
    public function updateUser(array $data);
    public function logoutUser(int $user_id);
    public function oauth(String $email, String $password, array $user);
}