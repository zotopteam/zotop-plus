<?php

namespace App\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:seed
                {module? : The module to use}
                {--database= : The database connection to use}
                {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the module\'s database with records';

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
        // 数据填充单个模块
        if ($module = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($module);
            $this->seed($module);
            return;
        }

        // 数据填充全部模块
        foreach ($this->laravel['modules']->installed() as $module) {
            $this->seed($module);
        }
    }

    /**
     * seed module
     * @param  Module $module 模块
     * @return void
     */
    private function seed($module)
    {
        $this->info(PHP_EOL . 'Seed the module:' . $module->getName() . '(' . $module->getTitle() . ')' . PHP_EOL);

        $seeders = Arr::wrap($module->getSeeders());

        if (empty($seeders)) {
            $this->info('Nothing to seed');
            return;
        }

        foreach ($seeders as $seeder) {

            if (!class_exists($seeder)) {
                $this->error('Class does not exiests: ' . $seeder);
                continue;
            }

            $this->info('Seeding:' . $seeder);

            $this->call('db:seed', [
                '--class' => $seeder,
                '--database' => $this->option('database'),
                '--force'    => $this->option('force'),
            ]);
        }
    }
}
