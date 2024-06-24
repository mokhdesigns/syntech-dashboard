<?php

namespace Syntech\Dashboard;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the command
        $this->commands([
            Console\Commands\CreateDashboard::class,
        ]);
    }

    public function boot()
    {
        // Bootstrapping, if necessary
    }
}
