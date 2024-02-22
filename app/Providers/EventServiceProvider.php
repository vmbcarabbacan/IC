<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\CustomerLogEvent;
use App\Listeners\CustomerLogListener;
use App\Events\CarLeadStatusEvent;
use App\Listeners\CarLeadStatusListener;
use App\Events\RoundrobinSalesAgentEvent;
use App\Listeners\RoundrobinSalesAgentListener;

// use App\Models\CarLeadTask;
// use App\Observers\CarLeadTaskObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerLogEvent::class => [
            CustomerLogListener::class
        ],
        CarLeadStatusEvent::class => [
            CarLeadStatusListener::class
        ],
        RoundrobinSalesAgentEvent::class => [
            RoundrobinSalesAgentListener::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // CarLeadTask::observe(CarLeadTaskObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
