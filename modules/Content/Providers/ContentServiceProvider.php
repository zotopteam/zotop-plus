<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\Content;
use Modules\Content\Models\Model;


class ContentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        // 定时任务
        $this->app->booted(function () {
            $schedule = $this->app->make(\Illuminate\Console\Scheduling\Schedule::class);
            $schedule->command('content:publish-future --schedule')->name('content-publish-future')->withoutOverlapping()->everyMinute();
        });
    }    

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
