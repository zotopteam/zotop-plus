<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZotopUpdateFormTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zotop:update-form-tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade: Update all form tag to z-form and z-field';

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
        // 获取所有模块里面的模板文件
        $files = $this->laravel['files']->allFiles(base_path('modules'));
        $files = collect($files)->filter(function ($file) {
            return strpos($file, '.blade.php');
        });

        // 获取app下面的stub文件
        $stubs = $this->laravel['files']->allFiles(base_path('app'));
        $stubs = collect($stubs)->filter(function ($stub) {
            return strpos($stub, '.stub');
        });

        $files = $files->merge($stubs);

        $bar = $this->output->createProgressBar($files->count());
        $bar->start();

        // 处理每个文件
        foreach ($files as $file) {
            $this->processFile($file);
            $bar->advance();
        }

        $bar->finish();

        $this->info("ok");
    }

    protected function processFile($file)
    {
        $content = $this->laravel['files']->get($file);

        $content = preg_replace('/{form(\s+[^}]+?)\s*}/s', '<z-form$1>', $content);
        $content = preg_replace('/{field(\s+[^}]+?)\s*}/s', '<z-field$1/>', $content);
        $content = str_replace('{/form}', '</z-form>', $content);

        $this->laravel['files']->put($file, $content);
    }
}
