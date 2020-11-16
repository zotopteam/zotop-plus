<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;

class DisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable
                {module : The module to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

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
     * @throws \App\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->isInstalled()) {

            if ($module->isDisabled()) {
                $this->error("Module [{$module}] has already disabled.");
                return;
            }

            $module->disable();

            $this->info("Module [{$module}] disable successfully.");
            $this->call('route:clear');
            return;
        }

        $this->error("Module [{$module}] does not installed.");
    }
}
