<?php

namespace Swarnajith\Empdupe;

use Illuminate\Support\ServiceProvider;

class EmpdupeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Swarnajith\Empdupe\EmpdupeController');
        $this->app->make('Swarnajith\Empdupe\EmpdupeGenerator');
        $this->loadViewsFrom(__DIR__.'/views', 'Empdupe');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }
}
