<?php

namespace App\Providers;

use App\Events\LogForDeletedEvents;
use App\Events\LogForRestoredEvents;
use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
use App\Listeners\LodDeletedListener;
use App\Listeners\LogCreateListener;
use App\Listeners\LogRestorelistener;
use App\Listeners\LogUpdatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        LogForStoredEvent::class => [
            LogCreateListener::class,
        ],

        LogForUpdatedEvents::class => [
            LogUpdatedListener::class,
        ],

        LogForDeletedEvents::class => [
            LodDeletedListener::class,
        ],

        LogForRestoredEvents::class => [
            LogRestorelistener::class,
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool {
        return false;
    }
}