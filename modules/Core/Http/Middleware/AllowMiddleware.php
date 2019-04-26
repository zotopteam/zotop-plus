<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class Authorization
 * Inspired by : https://github.com/spatie/laravel-authorize
 * @package Modules\Core\Http\Middleware
 */
class AllowMiddleware
{
    /**
     * @param $request
     * @param \Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next, $permission)
    {
        // 检查用户是否有权限 $permission
        if (! Auth::user()->allow($permission)) {
            abort(403);
        }

        return $next($request); 
    }

}
