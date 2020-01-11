<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install
                {module : The module to use}
                {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module.'; 

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
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if (! $module->isInstalled()) {
            $module->seed = $this->option('seed');
            $module->install();
            $this->info("Module [{$module}] install successfully.");
            return;
        }

        $this->error("Module [{$module}] has already installed.");
    }

}
