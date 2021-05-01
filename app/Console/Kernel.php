<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('debugbar:clear')->cron(config('debugbar.storage.clear', '0 3 * * *'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load([
            realpath(__DIR__ . '/Commands'),
            realpath(__DIR__ . '/../Modules/Commands'),
            realpath(__DIR__ . '/../Themes/Commands'),
        ]);

        require base_path('routes/console.php');
    }
}
