<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\GlobalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository extends GlobalService implements UserInterface {


    public function getUser(array $condition, array $with = [])
    {
        return $this->model(User::class, $condition, $with);
    }

    public function saveUser(array $data) {

        try {
            DB::beginTransaction();

            $exist = $this->checkUserByEmail($data['email']);

            if($exist)
                return false;

            $setting = $data['setting'];
            unset($data['setting']);

            $user = new User();
            $user->fill($data);
            $user->save();
            $user->userSetting()->create($setting);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'saveUser',
                "data" => $e
            ]);
            DB::rollBack();
            return false;
        }
    }

    public function updateUser($data) {
        try {
            DB::beginTransaction();

            $setting = $data['setting'];
            unset($data['setting']);
            
            if(empty($data['password'])) 
                unset($data['password']);

            $user = User::find($data['id']);
            $user->fill($data);
            $user->save();
            // $user->userSetting()->update($setting);

            $userSetting = UserSetting::where('user_id', $user->id)->first();
            $userSetting->fill($setting);
            $userSetting->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            $this->logError([
                "function" => 'updateUser',
                "data" => $e
            ]);
            DB::rollBack();
            return false;
        }

    }

    private function checkUserByEmail($email) {
        return User::where('email', $email)->first();
    }

}