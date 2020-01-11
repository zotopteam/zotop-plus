<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Action;

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
        // 钩子
        Action::fire('reboot', $this);

        // 发布模块和主题资源
        $this->call('module:publish');
        $this->call('theme:publish');

        // 清理缓存
        $this->call('cache:clear');

        // 清理路由缓存和配置缓存
        $this->call('optimize:clear');

        // 生产环境时自动优化系统，建立配置和路由缓存
        if ($this->laravel->environment('production')) {
            $this->call('optimize');
        }

        $this->info("Rebooted!");      
    }


}
