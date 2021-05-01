<?php

namespace Zotop\Modules\Support;

use Closure;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Finder\Finder;


class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Register schedules
     *
     * @param \Closure $callback
     * @author Chen Lei
     * @date 2020-12-01
     */
    protected function schedules(Closure $callback)
    {
        $this->app->booted(function () use ($callback) {
            $schedule = $this->app->make(Schedule::class);
            $callback($schedule);
        });
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @param array|string $paths
     * @throws \ReflectionException
     * @author Chen Lei
     * @date 2020-12-01
     */
    protected function loadCommands($paths)
    {
        $paths = collect($paths)->unique()->filter(function ($path) {
            return is_dir($path);
        })->toArray();

        if (empty($paths)) {
            return;
        }

        collect((new Finder)->in($paths)->files())->transform(function ($file) {
            return config('modules.namespace') . '\\' . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getRealPath(), config('modules.paths.modules') . DIRECTORY_SEPARATOR)
                );
        })->filter(function ($command) {
            return is_subclass_of($command, Command::class) && !(new ReflectionClass($command))->isAbstract();
        })->each(function ($command) {
            Artisan::starting(function ($artisan) use ($command) {
                $artisan->resolve($command);
            });
        });
    }
}
