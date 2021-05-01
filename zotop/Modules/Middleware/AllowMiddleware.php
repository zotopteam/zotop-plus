<?php

namespace Zotop\Modules\Middleware;

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
     * @param Request $request
     * @param \Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle(Request $request, \Closure $next, $permission)
    {
        return $next($request);
    }
}
