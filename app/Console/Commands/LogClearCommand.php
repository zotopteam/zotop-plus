<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the log files';

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
        $dir = storage_path('logs');

        // 清理log文件（保留.gitignore等隐藏文件）
        foreach ($this->laravel['files']->files($dir, false) as $file) {
            $this->laravel['files']->delete($file);
        }

        $this->info("Clear log successfully!");
    }
}
