<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class Authorization
 * Inspired by : https://github.com/spatie/laravel-authorize
 * @package Modules\Core\Http\Middleware
 */
class AllowMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * Authorization constructor.
     * @param Authentication $auth
     */
    public function __construct()
    {
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next, $permission)
    {
        // 检查用户是否有权限 $permission
        
        
        return $next($request);
    }

}
