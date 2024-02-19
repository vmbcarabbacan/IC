<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Traits\SystemTrait;
use Carbon\Carbon;

class GlobalService {

    use SystemTrait;

    public function getSetting($key) {
        return DB::table('app_configurations')->where('key', $key)->first();
    }

    public function getClient() {
        return DB::table('oauth_clients')->latest()->first();
    }

    public function model($model, $condition, $with = null) {
        $md = $model::where($condition);

        if($with) $md = $md->with($with);

        return $md->first();
    }

    public function currentUser() {
        return auth()->user() ? auth()->user()->id : $this->system();
    }

    public function systemDate() {
        return Carbon::now();
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

}