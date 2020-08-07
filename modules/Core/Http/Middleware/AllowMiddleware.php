<?php

namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::user()->allow($permission)) {
            abort(403, __('Sorry, you are forbidden from accessing this page.'));
        }

        return $next($request);
    }
}
