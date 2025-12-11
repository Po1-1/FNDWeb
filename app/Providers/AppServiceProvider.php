<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.navigation', function ($view) {
            $activeEvent = null;
            if (session()->has('active_event_id')) {
                $activeEvent = Event::find(session('active_event_id'));
            }
            $view->with('activeEvent', $activeEvent);
        });
    }
}
