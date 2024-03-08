<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// repositories
use App\Repositories\UserRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\WebsiteRepository;
use App\Repositories\MasterRepository;

// interfaces
use App\Interfaces\UserInterface;
use App\Interfaces\CustomerInterface;
use App\Interfaces\WebsiteInterface;
use App\Interfaces\MasterInterface;

// Observer
use App\Models\Customer;
use App\Observers\CustomerObserver;
use App\Models\CustomerDetails;
use App\Observers\CustomerDetailObserver;
use App\Models\CarDriverDetail;
use App\Observers\CarDriverDetailObserver;
use App\Models\CarLead;
use App\Observers\CarLeadObserver;
use App\Models\CarLeadTask;
use App\Observers\CarLeadTaskObserver;
use App\Models\UserSetting;
use App\Observers\UserSettingObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(WebsiteInterface::class, WebsiteRepository::class);
        $this->app->bind(MasterInterface::class, MasterRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        UserSetting::observe(UserSettingObserver::class);
        Customer::observe(CustomerObserver::class);
        CustomerDetails::observe(CustomerDetailObserver::class);
        CarDriverDetail::observe(CarDriverDetailObserver::class);
        CarLead::observe(CarLeadObserver::class);
        CarLeadTask::observe(CarLeadTaskObserver::class);
    }
}
