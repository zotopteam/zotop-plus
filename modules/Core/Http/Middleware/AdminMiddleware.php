<?php

namespace Modules\Core\Http\Middleware;

use Zotop\Modules\Routing\JsonMessageResponse;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // 检查是否为管理员登录
        if (Auth::check() && Auth::user()->isType(['super', 'admin'])) {
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
     *
     * @param $request
     * @param $response
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function terminate($request, $response)
    {
        if ($this->app['config']->get('core.log.enabled') && ($response instanceof JsonMessageResponse)) {

            $data = $response->getData();

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
