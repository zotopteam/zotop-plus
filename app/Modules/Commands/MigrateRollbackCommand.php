<?php

namespace App\Modules\Commands;

use App\Modules\Module;
use Illuminate\Console\Command;

class MigrateRollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-rollback
                {module? : The module to use}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--pretend : Dump the SQL queries that would be run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the last module migration';

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
        // 回滚单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->rollback($module);
            return;
        }

        // 回滚全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->rollback($module);
        }
    }

    /**
     * 迁移
     *
     * @param Module $module 模块
     * @return void
     */
    private function rollback(Module $module)
    {
        $this->info(PHP_EOL . 'Rollback the module:' . $module->getName() . '(' . $module->getTitle() . ')' . PHP_EOL);

        $path = $this->getMigrationPath($module);

        $this->call('migrate:rollback', [
            '--path'     => $path,
            '--realpath' => true,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
            '--step'     => $this->option('step'),
        ]);
    }


    /**
     * 获取源路径
     *
     * @param Module $module 模块
     * @return string
     */
    private function getMigrationPath(Module $module)
    {
        $path = $this->laravel['config']->get('modules.paths.dirs.migration');

        return $module->getPath($path);
    }
}
