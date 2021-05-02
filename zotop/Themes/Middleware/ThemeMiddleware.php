<?php

namespace Zotop\Themes\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 注册当前主题
        $this->app->singleton('current.theme', function ($app) {
            return $app['config']->get('modules.channels.' . $app['current.channel'] . '.theme');
        });

        // 激活主题
        $this->app['themes']->active($this->app['current.theme']);

        return $next($request);
    }

}
