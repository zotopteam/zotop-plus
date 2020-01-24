<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class ServiceProviderMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-provider
                {module : The module to use}
                {name? : The name to use}
                {--type=plain : The type of provider,allow: plain, event, route}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = 'ServiceProvider';

    /**
     * 目标路径键名
     * @var null
     */
    protected $dirKey = 'provider';

    /**
     * stub
     * @var string
     */
    protected $stub = 'provider';
 
    /**
     * 生成前准备
     * @return boolean
     */  
    public function prepare()
    {
        $this->stub = 'provider/'.$this->getTypeInput();

        return true;
    }

    /**
     * 路由服务容器固定名称为 RouteServiceProvider
     * @return string
     */
    public function getNameInput()
    {
        $name = $this->argument('name');

        if (empty($name)) {

            // 如果是事件和路由服务，默认名称为类型名称，否则为模块名称
            if (in_array($this->getTypeInput(), ['event', 'route'])) {
                $name = $this->getTypeInput();
            } else {
                $name = $this->getModuleName();
            }

        }

        return strtolower($name);
    }   

    /**
     * 获取输入的类型，支持,常规=plain, 事件=event, 路由=route
     * @return string
     */
    public function getTypeInput()
    {
        $type = strtolower($this->option('type'));

        if (! in_array($type, ['event', 'route'])) {
            return 'plain';
        }

        return $type;
    }
}
