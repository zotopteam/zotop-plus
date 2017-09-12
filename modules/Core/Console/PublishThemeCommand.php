<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishThemeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a theme\'s assets to the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($name = $this->argument('theme')) {
            return $this->publish($name);
        }

        $this->publishAll();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['theme', InputArgument::OPTIONAL, 'The name of theme will be used.'],
        ];
    }


    /**
     * 发布全部主题
     * 
     * @return void
     */
    public function publishAll()
    {
        foreach ($this->laravel['theme']->getList() as $theme) {
            $this->publish($theme->name);
        }
    }

    /**
     * 发布特定主题
     *
     * @param string $name
     */
    public function publish($name)
    {
        $theme = $this->laravel['theme']->find($name);

        if ( empty($theme) ) {
            $this->error("{$name} not exists!");
        }

        $source      = $theme->path.'/assets';
        $destination = $this->laravel['theme']->getAssetsPath($name);

        // 目录不存在自动创建
        if (!$this->laravel['files']->isDirectory($destination)) {
            $this->laravel['files']->makeDirectory($destination, 0775, true);
        }        

        // 拷贝整个assets文件夹到public下，使得js、css和图片等文件可访问
        if ($this->laravel['files']->copyDirectory($source, $destination)) {
            $this->line("<info>Published</info>: {$theme->name}");
        } else {
            $this->error($this->error);
        }  
    }
}
