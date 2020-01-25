<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Modules\Core\Models\Log;


class AdminMiddleware
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
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        // 管理员已经登录或者设置了admin.guest中间件继续运行
        if ( in_array('admin.guest', Route::getCurrentRoute()->gatherMiddleware()) || (Auth::check() && Auth::user()->isModel(['super','admin'])) ) {
            return $next($request);
        }

        // Ajax 禁止    
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        }
        
        // 转向登录页面
        return redirect()->guest(route('admin.login'));
    }

    /**
     * 在响应之后记录操作日志
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if ($this->app['config']->get('core.log.enabled') && ($response instanceof JsonResponse)) {
            
            $data = $response->getData();

            if (isset($data->type) && isset($data->content)) {

                Log::create([
                    'type'       => $data->type,
                    'content'    => $data->content,
                    'module'     => $this->app['current.module'],
                    'controller' => $this->app['current.controller'],
                    'action'     => $this->app['current.action'],
                    'url'        => $this->app['request']->fullUrl(),
                    'request'    => $this->app['request']->except(['_token']),
                ]);

            }
        }

    }    
}
