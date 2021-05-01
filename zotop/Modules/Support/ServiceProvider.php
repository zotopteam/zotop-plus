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
     * Register alias
     *
     * @param string $middleware
     * @param string $class
     * @return \Zotop\Modules\Support\ServiceProvider
     * @author Chen Lei
     * @date 2021-05-01
     */
    protected function middleware(string $middleware, string $class)
    {
        $this->app['router']->aliasMiddleware($middleware, $class);

        return $this;
    }

    /**
     * Register middlewares
     *
     * @param array $middlewares
     * @return \Zotop\Modules\Support\ServiceProvider
     * @author Chen Lei
     * @date 2021-05-01
     */
    protected function middlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware => $class) {
            $this->app['router']->aliasMiddleware($middleware, $class);
        }

        return $this;
    }

    /**
     * Register alias
     *
     * @param string $alias
     * @param string $class
     * @return \Zotop\Modules\Support\ServiceProvider
     * @author Chen Lei
     * @date 2021-05-01
     */
    public function alias(string $alias, string $class)
    {
        AliasLoader::getInstance()->alias($alias, $class);

        return $this;
    }

    /**
     * Register aliases
     *
     * @param array $aliases
     * @return \Zotop\Modules\Support\ServiceProvider
     * @author Chen Lei
     * @date 2021-05-01
     */
    public function aliases(array $aliases)
    {
        $loader = AliasLoader::getInstance();

        foreach ($aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }

        return $this;
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @param array|string $paths
     * @return \Zotop\Modules\Support\ServiceProvider
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
            return $this;
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

        return $this;
    }
}
