<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RebootCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'reboot
                            {--force : Force reboot app.}';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reboot the app';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 生产环境时建立配置和路由缓存
        if ($this->laravel->environment('production')) {
            $this->call('config:cache');
            $this->call('route:cache');            
        } else {
            $this->call('config:clear');
            $this->call('route:clear');           
        }
  
        $this->call('view:clear');
        $this->call('cache:clear');
        $this->call('debugbar:clear');
        $this->call('log:clear');
        $this->call('clear-compiled');
        
        $this->info("Rebooted!");      
    }


}
