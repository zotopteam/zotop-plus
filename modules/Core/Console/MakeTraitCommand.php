<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class MakeTraitCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:make-trait 
                            {name : The name of trait.} 
                            {module : The name of module will be used.} 
                            {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module traits';

    /**
     * 名称
     * @var string
     */
    protected $name;

    /**
     * 模块
     * @var Module
     */
    protected $module;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name   = Str::studly($this->argument('name'));
        $this->module = $this->laravel['modules']->findOrFail($this->argument('module'));

        $filepath = $this->getDestinationFilePath();

        if ($this->laravel['files']->exists($filepath)) {
            if (! $this->option('force')) {
                $this->error('The file already exists: '.$filepath.' ');
                return;
            }
        }

        $this->laravel['files']->put($filepath, $this->renderStub());

        $this->info('Created '.$filepath.'');
    }

    /**
     * 从stub生成文件
     *
     * @param $stub
     * @return string
     */
    private function renderStub()
    {
        $stub = $this->laravel['files']->get(__DIR__.'/stubs/trait.stub');

        return str_replace(
            [
                '$NAMESPACE$',
                '$CLASS$',
            ],
            [
                $this->getNamespace(),
                $this->name,
            ],
            $stub
        ); 
    }    

    /**
     *  获取文件最终生成路径
     * 
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        return  $this->module->getExtraPath('Traits'.DIRECTORY_SEPARATOR.$this->name.'.php');
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return 'Modules\\'.$this->module->getStudlyName().'\\Traits';
    } 
}
