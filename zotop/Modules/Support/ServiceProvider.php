<?php

namespace Zotop\Modules\Support;

use Closure;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
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
     * Register middlewares
     *
     * @param array $middlewares
     * @author Chen Lei
     * @date 2021-05-01
     */
    protected function middlewares(array $middlewares)
    {
        foreach ($middlewares as $name => $class) {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }

    /**
     * Register aliases
     *
     * @param array $aliases
     * @author Chen Lei
     * @date 2021-05-01
     */
    public function aliases(array $aliases)
    {
        $loader = AliasLoader::getInstance();

        foreach ($aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @param array|string $paths
     * @author Chen Lei
     * @date 2020-12-01
     */
    protected function loadCommands($paths)
    {
        $paths = collect($paths)->unique()->filter(function ($path) {
            return is_dir($path);
        })->transform(function ($path) {
            return realpath($path);
        })->toArray();

        if (empty($paths)) {
            return;
        }

        collect((new Finder)->in($paths)->files())->transform(function ($file) {
            return Str::of($file->getRealPath())
                ->between(base_path(), '.php')
                ->explode(DIRECTORY_SEPARATOR)
                ->filter()
                ->transform(function ($item) {
                    return Str::studly($item);
                })
                ->implode('\\');
        })->filter(function ($command) {
            return is_subclass_of($command, Command::class) && !(new ReflectionClass($command))->isAbstract();
        })->each(function ($command) {
            Artisan::starting(function ($artisan) use ($command) {
                $artisan->resolve($command);
            });
        });
    }
}
