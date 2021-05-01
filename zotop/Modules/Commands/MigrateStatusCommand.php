<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Module;
use Illuminate\Console\Command;

class MigrateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-status
                {module? : The module to use}
                {--database= : The database connection to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the status of module\'s migrations';

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
        // 迁移状态：单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->status($module);
            return;
        }

        // 迁移状态：全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->status($module);
        }
    }

    /**
     * 迁移
     *
     * @param Module $module 模块
     * @return void
     */
    private function status(Module $module)
    {
        $this->info(PHP_EOL . 'Show status of module:' . $module->getName() . '(' . $module->getTitle() . ')' . PHP_EOL);

        $path = $this->getMigrationPath($module);

        $this->call('migrate:status', [
            '--path'     => $path,
            '--realpath' => true,
            '--database' => $this->option('database'),
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
