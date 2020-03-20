<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZotopVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zotop:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the version of zotop';

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
        $name    = $this->laravel['config']->get('zotop.name');
        $version = $this->laravel['config']->get('zotop.version');
        $release = $this->laravel['config']->get('zotop.release');

        $this->info($name.':'.$version.'('.$release.')');
    }
}
