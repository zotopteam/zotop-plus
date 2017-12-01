<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module, Use: php artisan module:create module';

    /**
     * 主题名称
     * 
     * @var string
     */    
    protected $moduleName;

    /**
     * 主题路径
     * 
     * @var string
     */
    protected $modulePath;

    /**
     * 删除laravel-module模型中生成的多余文件
     * 
     * @var array
     */
    protected $removeFiles = [
        'Http/routes.php',
        'Http/Controllers/IndexController.php',        
        'Http/Controllers/$STUDLY_MODULE_NAME$Controller.php',        
        'Providers/$STUDLY_MODULE_NAME$ServiceProvider.php',
        'Resources/views/index.blade.php',
        'Resources/views/layouts/master.blade.php',
        'Config/config.php',
    ];

    /**
     * 删除laravel-module模型中生成的多余文件夹
     * 
     * @var array
     */
    protected $removeDirs = [
        'Resources/views/layouts',
    ];    

    /**
     * 创建laravel-module模型中缺少的文件
     * 
     * @var array
     */
    protected $createFiles = [
        'start.stub'              => 'start.php',
        'config.stub'             => 'config.php',
        'modulejson.stub'         => 'module.json',
        'module.png'              => 'module.png',
        'routes-front.stub'       => 'Routes/front.php',
        'routes-admin.stub'       => 'Routes/admin.php',
        'routes-api.stub'         => 'Routes/api.php',
        'route-provider.stub'     => 'Providers/RouteServiceProvider.php',        
        'lang/en/module.php'      => 'Resources/lang/en/$LOWERCASE_MODULE_NAME$.php',
        'lang/zh-Hans/module.php' => 'Resources/lang/zh-Hans/$LOWERCASE_MODULE_NAME$.php',
        'lang/zh-Hant/module.php' => 'Resources/lang/zh-Hant/$LOWERCASE_MODULE_NAME$.php',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->moduleName = $this->argument('name');
        $this->modulePath = $this->modulePath();

        // 调用laravel-module的make
        $this->call("module:make", [
            'name'    => [$this->moduleName],
            '--plain' => $this->option('plain'),
            '--force' => $this->option('force'),
        ]);

        // 删除laravel-module中多余的文件
        $this->removeFiles();

        // 创建laravel-module中缺失的文件
        $this->createFiles();

        // 创建控制器
        $this->createResources();

        $this->info('Ok');     
    }

    /**
     * 获取命令行传入的模块名称或者别名
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The module name'),
        );
    }

    protected function getOptions()
    {
        return [
            array('plain', 'p', InputOption::VALUE_NONE, 'Generate a plain module (without some resources).'),
            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when module already exist.'),
        ];
    }


    /**
     * 创建laravel-module中缺少的文件
     * 
     * @return void
     */
    private function createFiles()
    {
        foreach ($this->createFiles as $stub=>$file) {

            $path = $this->modulePath($file);

            // 如果不存在，尝试创建
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }

            $this->laravel['files']->put($path, $this->renderStub($stub));

            $this->info("Created : {$path}");
        }        
    }

    /**
     * 删除laravel-module中多余的文件
     * 
     * @return void
     */
    public function removeFiles()
    {
        foreach ($this->removeFiles as $file) {
            $this->laravel['files']->delete($this->modulePath($file));
        }

        foreach ($this->removeDirs as $dir) {
            $this->laravel['files']->deleteDirectory($this->modulePath($dir));
        }        
    }

    /**
     * 创建默认的控制器文件
     * 
     * @return void
     */
    public function createResources()
    {
        // 后台创建ModuleNameController
        $this->call('module:make-admin-controller', [
            'controller' => $this->moduleName().'Controller',
            'module'     => $this->moduleName()
        ]);

        // 前台创建IndexController
        $this->call('module:make-front-controller', [
            'controller' => 'IndexController',
            'module'     => $this->moduleName(),
            '--style'    => 'simple'
        ]);        

        // 创建默认的ModuleNameServiceProvider
        $this->call('module:make-provider', [
            'name'   => $this->moduleName() . 'ServiceProvider',
            'module' => $this->moduleName()
        ]);                  
    }       

    /**
     * 
     * 获取名称(驼峰)
     *
     * @return string
     */
    private function moduleName()
    {
        return studly_case($this->moduleName);
    }    

    /**
     * 返回当前模型下的路径
     * 
     * @param  string $path
     * @return string
     */
    private function modulePath($path = '')
    {
        return $this->laravel['modules']->getModulePath($this->moduleName()).$this->replaceVars($path);
    }

    /**
     * 获取stubs文件地址
     *
     * @param $filename
     * @return string
     */
    protected function stubPath($filename='')
    {
        $stubPath = __DIR__.'/stubs/';

        return $filename ? $stubPath.$filename : $stubPath;
    }       

    /**
     * 从stub生成文件
     *
     * @param $stub
     * @param $class
     * @return string
     */
    private function renderStub($stub)
    {
        $stub = $this->laravel['files']->get($this->stubPath($stub));

        return $this->replaceVars($stub);
    }       

    /**
     * 替换字符串中的变量
     * 
     * @param $stub
     * @param $class
     * @return string
     */
    private function replaceVars($str)
    {
        return str_replace(
            [
                '$MODULE_NAME$',
                '$LOWERCASE_MODULE_NAME$',
                '$PLURAL_LOWERCASE_MODULE_NAME$',
                '$STUDLY_MODULE_NAME$'
            ],
            [
                $this->moduleName(),
                strtolower($this->moduleName),
                strtolower(str_plural($this->moduleName)),
                $this->moduleName(),
            ],
            $str
        );        
    }
}
