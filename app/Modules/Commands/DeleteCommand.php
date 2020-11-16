<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;

class DeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:delete
                {module : The module to delete}';

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
     *  Execute the console command.
     *
     * @throws \App\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if (!$module->isInstalled()) {
            $module->delete();
            $this->info("Module [{$module}] delete successfully.");
            return;
        }

        $this->error("Module [{$module}] has already installed.");
    }
}
