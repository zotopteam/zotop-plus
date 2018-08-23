<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Route;
use Filter;

class LocaleMiddleware
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
        $this->app  = app();
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
        if ( $locale && $locale != $this->app->getLocale()) {
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
