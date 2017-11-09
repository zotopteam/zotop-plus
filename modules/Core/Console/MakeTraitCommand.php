<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Commands\GeneratorCommand;

use Illuminate\Support\Str;

class MakeTraitCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module traits. Use: php artisan module:make-trait CustomTrait module';

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

        $stub = new Stub('/trait.stub', [
            'NAMESPACE'    => $this->getClassNamespace($module),
            'CLASS'        => $this->getClass(),
        ]);

        $stub->setBasePath($path.'Console/stubs');

        return $stub->render();
    }

    /**
     *  获取文件最终生成路径
     * 
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        return $path . 'Traits/' . $this->getFileName() . '.php';
    }  

    /**
     * 文件名称转为驼峰
     * 
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return 'Traits';
    }     

    /**
     * 获取命令行传入的trait名称和模块名称(或者别名)
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The name of the trait.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }    
}
