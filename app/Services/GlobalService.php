<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Traits\SystemTrait;
use App\Traits\LoggingTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class GlobalService {

    use SystemTrait, LoggingTrait;

    public function getSetting($key) {
        return DB::table('app_configurations')->where('key', $key)->first();
    }

    public function updateConfiguration($key, $value) {
        DB::table('app_configurations')->where('key', $key)->update([
            'value' => $value
        ]);
    }

    public function getClient() {
        return DB::table('oauth_clients')->latest('id')->first();
    }

    public function model($model, $condition, $with = null) {
        $md = $model::where($condition);

        if($with) $md = $md->with($with);

        return $md->first();
    }

    public function currentUser() {
        return auth()->user() ? auth()->user()->id : $this->system();
    }

    public function currentUserName() {
        return auth()->user() ? auth()->user()->name : 'System';
    }

    public function systemDate() {
        return Carbon::now();
    }

    public function systemTimestamp($value = null) {
        if($value) 
            return Carbon::now()->addMinutes($value)->timestamp;

        return Carbon::now()->timestamp;
    }

    public function systemNow() { 
        return date('d/m/Y H:i',strtotime($this->systemDate()));
    }

    public function phoneNumberCheck($value, $val = '0') {
        return str_starts_with($value, $val) ? substr($value, 1) : $value;
    }

    public function countryCodeCheck($value) {
        if(str_starts_with($value, '+')) return $value;
        else return "+$value";
    }

    public function randomString($value = 15) {
        return Str::random($value);
    }

    public function encrypt($value) {
        return Crypt::encryptString($value);
    }

    public function decrypt($value) {
        try {
            return Crypt::decryptString($value);
        } catch(DecryptException $e) {
            $this->logError([
                "function" => "addCustomer",
                "data" => $e
            ]);
        }
    }

}