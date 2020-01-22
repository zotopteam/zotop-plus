<?php

namespace App\Modules\Commands;

use App\Modules\Exceptions\ModuleExistedException;
use App\Modules\Maker\GeneratorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    use GeneratorTrait;

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
        if ($this->hasModule()) {
            
            if (! $this->option('force')) {

                if ($this->laravel->runningInConsole()) {
                    $this->error('Module '.$this->getModuleStudlyName().' already exist!');
                    return;                    
                }

                throw new ModuleExistedException('Module '.$this->getModuleStudlyName().' already exist!', 1);
                
            }

            $this->laravel['files']->deleteDirectory($this->getModulePath());
        }
        
        
        // 创建文件
        $this->generateFiles();
        //创建图标
        $this->generateIcon();
        // 创建目录
        //$this->generateDirs();
        // 创建组件
        $this->generateResource();
        // 创建语言
        $this->generateLang();

        $this->info('Module '.$this->getModuleStudlyName().' created successfully!');
    }

    /**
     * 生成初始化文件
     * @return void
     */
    public function generateFiles()
    {
        $files = $this->laravel['config']->get('modules.paths.files');

        foreach ($files as $stub => $path) {
            $this->generateStubFile($stub, $path, $this->option('force'));
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
        $this->info('Created: '.$destinationPath);
    }

    /**
     * 生成初始化目录
     * @return void
     */
    public function generateDirs()
    {
        $dirs = $this->laravel['config']->get('modules.paths.dirs');

        foreach ($dirs as $key => $path) {
            $path = $this->getModulePath($path);

            if (! $this->laravel['files']->isDirectory($path)) {
                $this->laravel['files']->makeDirectory($path, 0755, true);
                $this->info('Created: '.$path);
            }
        }
    }

    /**
     * 创建资源
     * @return [type] [description]
     */
    public function generateResource()
    {
        $this->call('module:make-provider', [
            'module' => $this->getModuleStudlyName(),
            'name'   => $this->getModuleLowerName(),
        ]);

        $this->call('module:make-route-provider', [
            'module' => $this->getModuleStudlyName(),
        ]);

    }

    /**
     * 创建语言，默认创建可翻译语言包
     * @return void
     */
    public function generateLang()
    {
        $name = $this->getModuleStudlyName();

        $this->generateJsonLang([
            "{$name} title"       => "{$name} title",
            "{$name} description" => "{$name} description",
        ]);
    }
}
