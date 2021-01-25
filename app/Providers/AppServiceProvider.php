<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(128);
        $this->logSql();
    }

    /**
     * 记录sql日志
     *
     * @author Chen Lei
     * @date 2020-11-25
     */
    private function logSql()
    {
        // .env 中未开启 APP_LOG_SQL 时，不记录sql日志
        if (!config('app.log_sql')) {
            return;
        }

        // 监听并记录日志
        DB::listen(function ($query) {
            $sql = str_replace('?', '"' . '%s' . '"', $query->sql);
            $sql = vsprintf($sql, $query->bindings);
            $sql = str_replace("\\", "", $sql);
            Log::debug("[{$query->time}ms] " . $sql . PHP_EOL);
        });
    }
}
