<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\Generator;

class ModuleMakeCommand extends Command
{
    use Generator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make
                {module : The name of module will be created.}
                {--force : Force the operation to run when the module already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.'; 

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 覆盖模块
        if (is_dir($path = $this->getModulePath())) {
            
            if (! $this->option('force')) {
                $this->error('Module '.$this->getModuleStudlyName().' already exist!');
                return;
            }

            $this->laravel['files']->deleteDirectory($path);
        }
        
        // 创建文件夹
        // 创建文件
        $this->generateFiles();
        $this->generateIcon();
    }

    /**
     * 生成初始化文件
     * @return void
     */
    public function generateFiles()
    {
        $files = $this->laravel['config']->get('modules.paths.files');

        foreach ($files as $stub => $path) {
            $this->generateStubFile($stub, $path);
        }
    }

    /**
     * 生成图标
     * @return void
     */
    public function generateIcon()
    {
        $sourcePath      = $this->getStubPath('module.png');
        $destinationPath = $this->getModulePath('module.png');

        $this->laravel['files']->copy($sourcePath, $destinationPath);
    }

}
