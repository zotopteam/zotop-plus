<?php

namespace Modules\Navbar\Providers;

use Zotop\Modules\Support\ServiceProvider;

class NavbarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Load all commands in the directory
        // $this->loadCommands(realpath(__DIR__ . '/../Console'));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Define scheduling tasks
        // $this->schedules(function ($schedule) {
        //     $schedule->command('navbar:command-name')->everyMinute();
        // });
    }

}
