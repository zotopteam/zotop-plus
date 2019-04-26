<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Carbon;
use Route;
use Filter;

class LocaleMiddleware
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
        // 设置语言
        $this->setLocaleLanguage();

        return $next($request);
    }

    /**
     * 设置本地语言
     * 
     * @return void 
     */
    protected function setLocaleLanguage()
    {
        $locale = $this->app['current.locale'];

        // 当前语言设置
        if ($locale && $locale != $this->app->getLocale()) {
            $this->app->setLocale($locale);            
        }

        // Carbon 语言转换
        $locales = Filter::fire('carbon.locale.transform', [
            'zh-Hans' => 'zh',
            'zh-Hant' => 'zh_TW'
        ]);
        
        $locale  = isset($locales[$locale]) ? $locales[$locale] : $locale;
        Carbon::setLocale($locale);
    }


}
