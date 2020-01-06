<?php

namespace App\Support\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish
                            {module? : The module to use}
                            {--action=publish : Publish or unpublish the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish or unpublish the module\'s assets';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $action = $this->option('action');

        // 发布单个模块
        if ($module) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->$action($module);
            return;
        }

        // 发布全部模块
        foreach ($this->laravel['modules']->all() as $module) {

            if ($action=='publish' && $module->isDisabled()) {
                continue;
            }

            if ($action=='unpublish' && $module->isEnabled()) {
                continue;
            }            

            $this->$action($module);
        }
    }

    /**
     * 发布
     * @param  Module $module 模块
     * @return void
     */
    private function publish($module)
    {
        $sourcePath      = $this->getSourcePath($module);
        $destinationPath = $this->getDestinationPath($module);
        
        // 删除目标目录
        $this->files->deleteDirectory($destinationPath);

        // 复制资源到目标目录
        if (! $this->files->isDirectory($sourcePath)) {
            return;
        }

        // 创建目标文件夹
        if (! $this->files->isDirectory($destinationPath)) {
            $this->files->makeDirectory($destinationPath, 0775, true);
        }

        // 复制文件到目标目录
        if ($this->files->copyDirectory($sourcePath, $destinationPath)) {
            $this->info("Publish {$module} successfully!");
        }
    }

    /**
     * 取消发布
     * @param  Module $module 模块名称
     * @return void
     */
    public function unpublish($module)
    {        
        $destinationPath = $this->getDestinationPath($module);
        $this->files->deleteDirectory($destinationPath);
        $this->info("Unpublish {$module} successfully!");
    }

    /**
     * 获取源路径
     * @param  Module $module 模块
     * @return string
     */
    private function getSourcePath($module)
    {
        $path = $this->laravel['config']->get('modules.paths.generator.assets.path');

        return $module->getPath($path);
    }

    /**
     * 获取目标路径
     * @param  Module $module 模块
     * @return string
     */
    public function getDestinationPath($module)
    {
        $path = $this->laravel['config']->get('modules.paths.assets');

        return $path.DIRECTORY_SEPARATOR.$module->getLowerName();
    }

}
