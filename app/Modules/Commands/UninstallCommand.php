<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:uninstall
                {module : The module to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the specified module.'; 

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

        if ($module->isInstalled()) {
            
            $module->uninstall();
            
            $this->call('route:clear');            
            $this->info("Module [{$module}] uninstall successfully.");
            return;
        }

        $this->error("Module [{$module}] does not installed.");
    }

}
