<?php

namespace App\Support\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate
                {module? : The module to use}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules.'; 

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
        // 迁移单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->migrate($module);
            return;
        }

        // 迁移全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->migrate($module);
        }
    }

    /**
     * 迁移
     * @param  Module $module 模块
     * @return void
     */
    private function migrate($module)
    {
        $this->info(PHP_EOL.'Migrate the module:'.$module->getName().'('.$module->getTitle().')'.PHP_EOL);

        $path = $this->getMigrationPath($module);

        $this->call('migrate', [
            '--path'     => $path,
            '--realpath' => true,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
            '--step'     => $this->option('step'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $module->getName()
            ]);
        }
    }


    /**
     * 获取源路径
     * @param  Module $module 模块
     * @return string
     */
    private function getMigrationPath($module)
    {
        $path = $this->laravel['config']->get('modules.paths.generator.migration.path');

        return $module->getPath($path);
    }
}
