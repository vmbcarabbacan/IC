<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// repositories
use App\Repositories\UserRepository;
use App\Repositories\CustomerRepository;

// interfaces
use App\Interfaces\UserInterface;
use App\Interfaces\CustomerInterface;

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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Customer::observe(CustomerObserver::class);
        CustomerDetails::observe(CustomerDetailObserver::class);
        CarDriverDetail::observe(CarDriverDetailObserver::class);
        CarLead::observe(CarLeadObserver::class);
        CarLeadTask::observe(CarLeadTaskObserver::class);
    }
}
