<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Commands\GeneratorCommand;

class AdminControllerCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';    

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:make-admin-controller 
                            {controller : The name of the admin controller class.} 
                            {module : The name of module will be used.} 
                            {--style=resource : The style of controller.} 
                            {--model : The model of controller.} 
                            {--force : Overwrite any existing files.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new admin controller for the module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->createViews();
    }

    /**
     * 创建laravel-module中缺少的文件
     * 
     * @return void
     */
    private function createViews()
    {   

        foreach ($this->getViewFiles() as $stub=>$file) {

            // 获取view文件地址
            $path = $this->getViewPath($file);

            // 如果不是强制生成，禁止覆盖已经存在的文件
            if (!$this->option('force') && $this->laravel['files']->exists($path)) {
                $this->info("File : {$path} already exists.");
                continue;
            }            

            // 如果不存在，尝试创建
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }          

            // 写入文件
            $this->laravel['files']->put($path, $this->renderViewStub($stub));

            $this->info("Created : {$path}");
        }        
    }

    /**
     * 控制器实际路径
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        return $path . 'Http/Controllers/Admin/' . $this->getControllerName() . '.php';
    }

    /**
     * 渲染模板
     * 
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());
        $path   = $this->laravel['modules']->getModulePath('Core');

        $stub = new Stub($this->getStubName(), [
            'MODULENAME'       => $module->getStudlyName(),
            'CONTROLLERNAME'   => $this->getControllerName(),
            'LOWER_CONTROLLER' => $this->getLowerControllerShortName(),
            'NAMESPACE'        => $module->getStudlyName(),
            'CLASS_NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'            => $this->getControllerName(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'MODEL'            => $this->getModelName(),
            'LOWER_MODEL'      => $this->getLowerModelName(),
            'PLURAL_MODEL'     => $this->getPluralModelName(),
        ]);

        $stub->setBasePath($path.'Console/stubs');

        return $stub->render();
    }


    /**
     * 获取ControllerName
     * 
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = studly_case($this->argument('controller'));

        if (str_contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * 获取Controller短名称，不含Conroller
     * 
     * @return array|string
     */
    protected function getControllerShortName()
    {
        $controller = $this->getControllerName();
        $controller = substr($controller, 0, -10);

        return $controller;
    }

    /**
     * 获取Controller名称，不含Conroller
     * 
     * @return array|string
     */
    protected function getLowerControllerShortName()
    {
        return strtolower($this->getControllerShortName());
    }    

    /**
     * 获取默认命名空间
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return 'Http\Controllers\Admin';
    }

    /**
     * 获取默认使用的数据模型
     * 
     * @return string
     */
    public function getModelName()
    {
        $model = $this->option('model');

        if ($model) {
            return studly_case($model);
        }

        return $this->getControllerShortName();
    }

    /**
     * 获取默认使用的数据模型小写名称
     * 
     * @return string
     */    
    public function getLowerModelName()
    {
        return strtolower($this->getModelName());
    }

    /**
     * 获取复数的模型名称
     * 
     * @return string
     */
    public function getPluralModelName()
    {
        return str_plural($this->getLowerModelName());
    }

    /**
     *
     * 获取对应风格的控制器stub文件
     * 
     * @return string
     */
    private function getStubName()
    {
        $style = strtolower($this->option('style'));

        return "/admin-controller-{$style}.stub";
    }

    /**
     * 创建默认的 view 文件
     * 
     * @return string 
     */
    private function getViewFiles()
    {
        $style = strtolower($this->option('style'));

        $path = $this->laravel['modules']->getModulePath('core'). 'Console/stubs/views/admin-controller/'.$style;

        $stubs = $this->laravel['files']->files($path);

        $files = [];

        foreach ($stubs as $stub) {

            $stubName = $stub->getFileName();
            $stubPath = $stub->getRealPath();
            
            $files[$stubPath] = basename($stubName,'.stub').'.blade.php';
        }

        return $files;      
    }

    /**
     * 获取view文件路径
     * 
     * @param  string $file 
     * @return string       
     */
    private function getViewPath($file='')
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $path = $path . 'Resources/views/admin/'.$this->getLowerControllerShortName().'/';

        return $file ? $path . $file : $path;
    }

    /**
     * 渲染viewstub
     * 
     * @param  string $stub 
     * @return string       
     */
    private function renderViewStub($stub)
    {
        $stub   = $this->laravel['files']->get($stub);
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $params = [
            'MODULENAME'       => $module->getStudlyName(),
            'CONTROLLERNAME'   => $this->getControllerName(),
            'LOWER_CONTROLLER' => $this->getLowerControllerShortName(),
            'NAMESPACE'        => $module->getStudlyName(),
            'CLASS_NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'            => $this->getControllerName(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getModuleName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
            'MODEL'            => $this->getModelName(),
            'LOWER_MODEL'      => $this->getLowerModelName(),
            'PLURAL_MODEL'     => $this->getPluralModelName(),       
        ];

        foreach ($params as $key => $value) {
            $stub = str_replace('$'.$key.'$', $value, $stub);
        }

        return $stub;
    }


}
