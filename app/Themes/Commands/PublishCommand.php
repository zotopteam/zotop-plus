<?php

namespace App\Themes\Commands;

use App\Themes\Theme;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:publish
                            {theme? : The theme to use}
                            {--action=publish : Publish or unpublish the theme}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish or unpublish theme\'s assets to the application';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \App\Themes\NotFoundException
     */
    public function handle()
    {
        $action = $this->option('action');

        if ($theme = $this->argument('theme')) {
            $theme = $this->laravel['themes']->findOrFail($theme);
            $this->$action($theme);
            return;
        }

        // 发布全部模块
        foreach ($this->laravel['themes']->all() as $theme) {
            $this->$action($theme);
        }

    }

    /**
     * 发布主题
     *
     * @param \App\Themes\Theme $theme
     * @author Chen Lei
     * @date 2020-11-07
     */
    private function publish(Theme $theme)
    {
        $sourcePath = $this->getSourcePath($theme);
        $destinationPath = $this->getDestinationPath($theme);

        // 删除目标目录
        $this->files->deleteDirectory($destinationPath);

        // 复制资源到目标目录
        if (!$this->files->isDirectory($sourcePath)) {
            return;
        }

        // 创建目标文件夹
        if (!$this->files->isDirectory($destinationPath)) {
            $this->files->makeDirectory($destinationPath, 0775, true);
        }

        // 复制文件到目标目录
        if ($this->files->copyDirectory($sourcePath, $destinationPath)) {
            $this->info("Publish {$theme} successfully!");
        }
    }

    /**
     * 取消发布
     *
     * @param \App\Themes\Theme $theme 模块名称
     * @return void
     */
    public function unpublish(Theme $theme)
    {
        $destinationPath = $this->getDestinationPath($theme);
        $this->files->deleteDirectory($destinationPath);
        $this->info("Unpublish {$theme} successfully!");
    }

    /**
     * 获取源路径
     *
     * @param \App\Themes\Theme $theme 模块
     * @return string
     */
    private function getSourcePath(Theme $theme)
    {
        return $theme->getPath('assets');
    }

    /**
     * 获取目标路径
     *
     * @param \App\Themes\Theme $theme 模块
     * @return string
     */
    public function getDestinationPath(Theme $theme)
    {
        $path = $this->laravel['config']->get('themes.paths.assets');

        return $path . DIRECTORY_SEPARATOR . $theme->getLowerName();
    }
}
