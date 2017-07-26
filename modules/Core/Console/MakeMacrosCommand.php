<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Commands\GeneratorCommand;

class MakeMacrosCommand extends GeneratorCommand
{
    use ModuleCommandTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-macros';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module macros. Use: php artisan module:make-macros module';

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取stub模板
     * 
     * @return [type] [description]
     */
    public function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());
        $path   = $this->laravel['modules']->getModulePath($this->getModuleName());

        $stub = new Stub('/macros.stub', []);
        $stub->setBasePath($path.'Console/stubs');

        return $stub->render();
    }

    /**
     * 获取文件最终生成路径模板
     * 
     * @return [type] [description]
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        return $path . 'macros.php';
    } 

    /**
     * 获取命令行传入的模块名称或者别名
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module name, Like: php artisan module:make-macros core'],
        ];
    }
}
