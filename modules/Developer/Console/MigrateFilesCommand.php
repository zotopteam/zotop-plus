<?php

namespace Modules\Developer\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
                            {--m|mode=migrate :  Set migrate exection mode, supported : migrate, refresh, rollback}';

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
        $mode = $this->option('mode');

        if (! in_array($mode, ['migrate', 'refresh', 'reset']) ) {
            $this->error("Invalid migrate mode: {$mode}");
            return false;
        }

        $this->line("Migrate mode: {$mode}");

        // 全部迁移文件
        $files = array_wrap($this->argument('files'));

        // 临时迁移文件夹路径
        $path = storage_path('temp/migrate-files-'.date('YmdHis'));

        try {
            // 创建临时文件夹
            $this->laravel['files']->makeDirectory($path);

            // 复制迁移文件到临时文件夹
            foreach ($files as $file) {
                $file   = str_start($file, $this->laravel->basePath());

                if ($this->laravel['files']->exists($file) && $this->laravel['files']->copy($file, $path .'/'. basename($file))) {
                    $this->line("Migrate file: {$file}");
                } else {
                    $this->error("Migrate file not found: {$file}");
                }
            }

            // 迁移选项：文件夹为相对路径
            $options = [
                '--path' => str_replace(base_path(), '', $path),
                '--force' => $this->option('force')
            ];

            // 迁移
            switch ($mode) {
                 case 'reset':
                    //回滚所有文件迁移
                    $this->callSilent('migrate:reset', $options);
                    break;
                 case 'refresh':
                    // 回滚并迁移
                    $this->callSilent('migrate:refresh', $options);
                    break;                 
                 default:
                    // 迁移所有文件
                    $this->callSilent('migrate', $options);
                    break;
            }     

        } finally {
            $this->laravel['files']->deleteDirectory($path);
        }
    }
}
