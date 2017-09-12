<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Commands\GeneratorCommand;

class FrontControllerCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-front-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful front controller for the module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->createViews();

        //$this->info('ok');
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

            // 如果不存在，尝试创建
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }

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

        return $path . 'Http/Controllers/' . $this->getControllerName() . '.php';
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
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerName(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]);

        $stub->setBasePath($path.'Console/stubs');

        return $stub->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('controller', InputArgument::REQUIRED, 'The name of the front controller class.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * Get the console command options.
     * 
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when controller already exist.'],
        ];
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
     * 获取Controller名称，不含Conroller
     * 
     * @return array|string
     */
    protected function getLowerControllerName()
    {
        $controller = $this->getControllerName();
        $controller = substr($controller, 0, -10);

        return strtolower($controller);
    }    

    /**
     * 获取默认命名空间
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Http\Controllers';
    }

    /**
     *
     * 获取控制器stubName
     * 
     * @return string
     */
    private function getStubName()
    {
        if ($this->option('plain') === true) {
            return '/front-controller-plain.stub';
        }

        return '/front-controller.stub';
    }

    /**
     * 创建默认的 view 文件
     * 
     * @return [type] [description]
     */
    private function getViewFiles()
    {
        if ($this->option('plain') === true) {
            return [
                'index.stub' => 'index.blade.php',
            ];
        }

        return [
            'index.stub'  => 'index.blade.php',
            'create.stub' => 'create.blade.php',
            'edit.stub'   => 'edit.blade.php',
        ];      
    }

    /**
     * 获取view文件路径
     * 
     * @param  string $file [description]
     * @return [type]       [description]
     */
    private function getViewPath($file='')
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $path = $path . 'Resources/views/front/'.$this->getLowerControllerName().'/';

        return $file ? $path . $file : $path;
    }

    /**
     * 渲染viewstub
     * 
     * @param  [type] $stub [description]
     * @return [type]       [description]
     */
    private function renderViewStub($stub)
    {
        $path = $this->laravel['modules']->getModulePath('core');

        $path = $path . 'Console/stubs/views/front/'.$stub;

        $stub = $this->laravel['files']->get($path);

        return str_replace(
            [
                '$MODULE_NAME$',
                '$CONTROLLER_NAME$',
            ],
            [
                $this->getModuleName(),
                $this->getControllerName()
            ],
            $stub
        );
    }


}
