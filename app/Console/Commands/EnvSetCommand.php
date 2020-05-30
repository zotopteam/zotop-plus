<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class EnvSetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set .env key value';

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
        $key   = strtoupper($this->argument('key'));
        $value = $this->argument('value');

        // 获取env文件数据
        $env = $this->laravel->environmentFilePath();

        $str = "\n" . $this->files->get($env) . "\n";

        // 获取原有的行
        $start = strpos($str, "{$key}=");
        $end   = strpos($str, "\n", $start);
        $old   = substr($str, $start, $end - $start);

        // 如果不存在则追加，存在则替换
        if (!$start || !$end || !$old) {
            $str .= "{$key}={$value}\n";
        } else {
            $str = str_replace($old, "{$key}={$value}", $str);
        }

        $str = trim($str, "\n");

        $this->files->put($env, $str);

        $this->info("Set {$key} successfully!");
    }
}
