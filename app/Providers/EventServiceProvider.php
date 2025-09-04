<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\RecoveryNoteCreated;
use App\Listeners\RecalculateSemesterAverage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            RecoveryNoteCreated::class,
            RecalculateSemesterAverage::class
        );
    }
}
