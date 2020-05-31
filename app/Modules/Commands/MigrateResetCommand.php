<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;

class MigrateResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-reset
                {module? : The module to use}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--pretend : Dump the SQL queries that would be run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback module\'s all database migration';

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
        // 回滚单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->reset($module);
            return;
        }

        // 回滚全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->reset($module);
        }
    }

    /**
     * 回滚
     * @param  Module $module 模块
     * @return void
     */
    private function reset($module)
    {
        $this->info(PHP_EOL . 'Reset the module:' . $module->getName() . '(' . $module->getTitle() . ')' . PHP_EOL);

        $path = $this->getMigrationPath($module);

        $this->call('migrate:reset', [
            '--path'     => $path,
            '--realpath' => true,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
        ]);
    }


    /**
     * 获取源路径
     * @param  Module $module 模块
     * @return string
     */
    private function getMigrationPath($module)
    {
        $path = $this->laravel['config']->get('modules.paths.dirs.migration');

        return $module->getPath($path);
    }
}
