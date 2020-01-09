<?php

namespace App\Support\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class DeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:delete
                {module : The module to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the specified module.'; 

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
            $module->delete();
            $this->info("Module [{$module}] delete successfully.");
            return;
        }

        $this->error("Module [{$module}] has already installed.");
    }

}
