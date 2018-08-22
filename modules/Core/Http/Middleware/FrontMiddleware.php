<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;

class FrontMiddleware
{
    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */       
    protected $app;    

    /**
     * 初始化
     */
    public function __construct() {
        $this->app = app();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->app->singleton('current.theme',function() {
            return 'default';
        });

        $this->app->singleton('current.locale',function() {
            return config('core.locale', $this->app->getLocale());
        });

        return $next($request);
    }
}
