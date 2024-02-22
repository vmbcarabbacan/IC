<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $expiry = $this->getSetting('token_expiry');
        $refresh = $this->getSetting('token_refresh');
        if($expiry) {
            Passport::tokensExpireIn(Carbon::now()->addMinutes($expiry->value));
            Passport::refreshTokensExpireIn(Carbon::now()->addMinutes($refresh->value));
        }
    }

    private function getSetting($key) {
        return DB::table('app_configurations')->where('key', $key)->first();
    }
}
