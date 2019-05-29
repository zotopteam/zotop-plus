<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Module;

class CreateThemeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'theme:create 
                            {name : The name of theme.} 
                            {--type=front : The style of theme, options: admin|front .} 
                            {--force : Overwrite any existing theme.}';  

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme! php artisan theme:create themeName';

    /**
     * 主题名称
     * 
     * @var string
     */    
    protected $themeName;

    /**
     * 主题路径
     * 
     * @var string
     */
    protected $themePath;

    /**
     * 主题类型
     * 
     * @var string
     */
    protected $themeType;

    /**
     * 创建laravel-module模型中缺少的文件
     * 
     * @var array
     */
    protected $createFiles = [
        'theme-json.stub'  => 'theme.json',
        'theme-start.stub' => 'start.php',
    ];
    
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
        // 主题名称和路径
        $this->themeName = strtolower($this->argument('name'));
        $this->themePath = $this->themePath($this->themeName);

        // 可选参数
        $this->themeType = $this->option('type');

        // 判断目录是否存在
        if ( $this->laravel['files']->isDirectory($this->themePath) ) {            
            
            //强制生成的时候删除已经存在目录
            if ( $this->option('force') ){
                $this->laravel['files']->deleteDirectory($this->themePath);
                $this->info("Delete the theme [{$this->themeName}]");
            } else {
                return $this->error("The theme [{$this->themeName}] already exists");
            }            
        }

        // $this->laravel['files']->makeDirectory($this->themePath, 0775, true);

        // 创建主题
        $this->createTheme();

        // 创建默认文件
        $this->createFiles();

        $this->info("Create theme [{$this->themeName}] Successfully!");
    }

    /**
     * 
     * 获取主题和文件路径
     * @param  string $name name
     * @param  string $file filename
     * @return path
     */
    public function themePath($name, $file=null)
    {
        $path = config('stylist.themes.paths', [base_path('/themes')])[0];

        return $file ? $path."/$name/$file" : $path."/$name";
    }

    /**
     * 获取stubs文件地址
     *
     * @param $filename
     * @return string
     */
    protected function stubPath($file='')
    {
        $stubPath = __DIR__.'/stubs';

        return $file ? $stubPath.'/'.$file : $stubPath;
    }     

    /**
     * 创建文件
     * 
     * @return void
     */
    public function createFiles()
    {
        foreach ($this->createFiles as $stub => $file) {
        
            // 文件路径
            $path = $this->themePath.'/'.$file;

            // 如果不存在，尝试创建
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }

            $this->laravel['files']->put($path, $this->renderStub($stub));
        }       
    }

    /**
     * 从stub生成文件
     *
     * @param $stub
     * @return string
     */
    private function renderStub($stub)
    {
        $stub = $this->laravel['files']->get($this->stubPath($stub));

        return str_replace(
            [
                '$THEME_NAME$',
                '$LOWERCASE_THEME_NAME$',
                '$THEME_DESCRIPTION$',
                '$THEME_TYPE$'
            ],
            [
                $this->themeName,
                strtolower($this->themeName),
                '',
                $this->themeType,
            ],
            $stub
        ); 
    }

    /**
     * 创建基本文件结构
     * 
     * @return void
     */
    public function createTheme()
    {
        $themePath = __DIR__.'/theme';

        $this->laravel['files']->copyDirectory($themePath, $this->themePath);

        // 自动拷贝前端模板
        // if ($this->themeType == 'front') {        
        //     foreach (Module::getOrdered() as $module) {
        //         $name = $module->getLowerName();
        //         $path = $module->getPath().'/Resources/views/'.$this->themeType;
        //         if ($this->laravel['files']->isDirectory($path)) {
        //             $this->laravel['files']->copyDirectory($path, $themePath.'/views/'.$name);
        //         }
        //     }
        // }         
    }  

}
