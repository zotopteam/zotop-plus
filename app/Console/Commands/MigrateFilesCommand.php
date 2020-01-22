<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateFilesCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'migrate:files 
                            {files* : Files path sperate by space.}
                            {--f|force : Force the operation to run when in production}
                            {--m|mode=migrate :  Set migrate exection mode, supported : migrate, rollback, refresh, reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate specific files.';

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
        // 迁移模式
        $mode = strtolower($this->option('mode'));

        if (! in_array($mode, ['migrate', 'refresh', 'reset', 'migrate-refresh']) ) {
            $this->error("Invalid migrate mode: {$mode}");
            return false;
        }

        // 全部迁移文件
        $files = Arr::wrap($this->argument('files'));

        // 临时迁移文件夹路径
        $path = storage_path('temp/migrate-files-'.$mode.'-'.date('YmdHis').rand(1000,9999));

        try {
            // 创建临时文件夹
            $this->laravel['files']->makeDirectory($path);

            // 复制迁移文件到临时文件夹
            foreach ($files as $file) {
                $file   = Str::start($file, $this->laravel->basePath());

                if ($this->laravel['files']->exists($file) && $this->laravel['files']->copy($file, $path .'/'. basename($file))) {
                    $this->line(Str::studly($mode).": {$file}");
                } else {
                    $this->error("Migrate file does not found or copied: {$file}");
                }
            }

            // 迁移选项：文件夹为相对路径
            $options = [
                '--path'     => $path,
                '--realpath' => true,
                '--force'    => $this->option('force')
            ];

            // 迁移
            switch ($mode) {
                 case 'migrate':
                    // 迁移文件
                    $this->callSilent('migrate', $options);                    
                    break;                  
                 case 'reset':
                    //回滚所有文件迁移
                    $this->callSilent('migrate:reset', $options);
                    break;
                 case 'refresh':
                    // 回滚并迁移
                    $this->callSilent('migrate:refresh', $options);
                    break;
            }     

        } finally {
            $this->laravel['files']->deleteDirectory($path);
        }
    }
}
