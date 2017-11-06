<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Core\Models\Config;

class ExecuteCommand extends Command
{
    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'execute';    

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:execute 
                            {action : The name of the action.} 
                            {module : The name of module will be used.} 
                            {--seed : with seeder.}
                            {--force : force execute.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute install/uninstall/update command for the local module.';
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
        $action = $this->argument('action');
        $module = $this->argument('module');

        $module = $this->laravel['modules']->find($module);

        if (!$module) {
            $this->error("The module [{$this->argument('module')}] does not exists.");
            return false;
        }

        switch (strtolower($action)) {
            case 'install':
                $this->install($module);
                break;
            case 'uninstall':
                $this->uninstall($module);
                break;  
            case 'update':
                $this->update($module);
                break;                                              
            default:
                $this->error("Execute command [{$action}] does not exists.");
                break;
        }
    }

    /**
     * 模块安装
     * 
     * @param  object $module 模块
     * @return mixed
     */
    public function install($module)
    {
        if (!$this->option('force') && $module->json()->get('installed',0)) {
            $this->info("This module aready installed");
            return false;
        }

        $this->info("Starting");

        $name = $module->getName();

        // Migrate
        $this->info("Migrate");
        $this->call('module:migrate', ['module' => $name, '--force'=>true]);

        // 
        if ($this->option('seed')) {
            $this->info("Seed");
            $this->call('module:seed', ['module' => $name, '--force'=>true]);
        }

        //写入config
        $configFile = $module->getPath().'/config.php';

        if ($this->laravel['files']->exists($configFile)) {
            
            $this->info("Config");

            if ($configs = require $configFile) {
                
                Config::set($name, $configs);
            }          
        }

        // Publish Config
        // $this->call('module:publish-config', ['module' => $name]);
        
        // Publish Assets
        $this->call('module:publish', ['module' => $name]);


        // Update module.json
        $module->json()->set('active', 1)->set('installed', 1)->save();

        $this->call('reboot');      
        
        $this->info("success");
    }

    /**
     * 模块卸载
     * 
     * @param  object $module 模块
     * @return mixed
     */
    public function uninstall($module)
    {
        $name = $module->getName();

        if (!$this->option('force') && $module->json()->get('installed',0)) {
            $this->info("This module aready installed");
            return false;
        }

        // 核心模块不能卸载
        if (in_array(strtolower($name), $this->laravel['config']->get('modules.cores',['core']))) {
            return $this->error('This module is the cores, can not uninstall');
        }

        $this->info("Starting");

        // TODO ：依赖检查，某些模块互相依赖，如果卸载并删除数据表，会导致错误，所以卸载前应进行依赖检查
        // coding……
        
        // migrate-reset
        $this->info("Migrate reset");
        $this->call('module:migrate-reset', ['module' => $name]);

        // 删除配置
        $this->info("Config forget");
        Config::forget($name);

        // 清理文件
        $this->info("Clear");
        
        $this->laravel['files']->deleteDirectory($this->laravel['modules']->assetPath($name));

        $this->laravel['files']->delete($this->laravel->bootstrapPath("cache/{$module->getSnakeName()}_module.php"));

        // update module.json
        $module->json()->set('active', 0)->set('installed', 0)->save();        
        
        $this->call('reboot');
        
        $this->info("success");
    }

    /**
     * 模块升级
     * 
     * @param  object $module 模块
     * @return mixed
     */
    public function update($module)
    {
        $this->info("Developing");
    }    
}
