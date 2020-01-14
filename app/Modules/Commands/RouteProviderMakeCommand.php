<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class RouteProviderMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-route-provider
                {module : The module to use}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the route service provider for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = 'ServiceProvider';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'provider';

    /**
     * stub
     * @var string
     */
    protected $stub = 'route_provider';

    /**
     * 路由服务容器固定名称为 RouteServiceProvider
     * @return string
     */
    public function getArgumentName()
    {
        return 'Route';
    }    

}
