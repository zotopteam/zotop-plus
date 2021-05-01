<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Module;
use Illuminate\Console\Command;

class MigrateRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-refresh
                {module? : The module to use}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh module\'s all database migration';

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
     * @throws \Zotop\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        // 回滚并迁移单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->refresh($module);
            return;
        }

        // 回滚并迁移全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->refresh($module);
        }
    }

    /**
     * 回滚并迁移
     *
     * @param Module $module 模块
     * @return void
     */
    private function refresh(Module $module)
    {
        $this->info(PHP_EOL . 'Refresh the module:' . $module->getName() . '(' . $module->getTitle() . ')' . PHP_EOL);

        $this->call('module:migrate-reset', [
            'module'     => $module->getName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        $this->call('module:migrate', [
            'module'     => $module->getName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $module->getName(),
            ]);
        }
    }
}
