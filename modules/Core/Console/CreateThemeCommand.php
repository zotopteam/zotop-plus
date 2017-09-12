<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateThemeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:create';    

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
    protected $themeType='front';

    /**
     * 创建laravel-module模型中缺少的文件
     * 
     * @var array
     */
    protected $createFiles = [
        'theme-json.stub'  => 'theme.json'
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
        $this->themeName = $this->argument('name');
        $this->themePath = $this->themePath($this->themeName);

        // 可选参数
        $admin = $this->option('admin');
        $force = $this->option('force');

        if ( $admin ) {
            $this->themeType = 'admin';
        }

        // 判断目录是否存在
        if ( $this->laravel['files']->isDirectory($this->themePath) ) {            
            
            //强制生成的时候删除已经存在目录
            if ( $force ){
                $this->laravel['files']->deleteDirectory($this->themePath);
                $this->info("Delete the theme [{$this->themeName}]");
            } else {
                return $this->error("The theme [{$this->themeName}] already exists");
            }            
        }

        // 创建默认文件
        $this->createFiles();

        $this->info("Create theme [{$this->themeName}] Successfully!");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The Theme name'],
        ];
    }

    /**
     * 可选项，强制生成和生成后台主题选项
     * 
     * @return [type] [description]
     */
    protected function getOptions()
    {
        return [
            array('admin', null, InputOption::VALUE_NONE, 'Generate a admin theme.'),
            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when theme already exist.'),
        ];
    }   

    /**
     * 获取主题和文件路径
     * 
     * @return [type] [description]
     */
    public function themePath($name, $file='')
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
     * 拷贝主题基本文件到主题中
     * 
     * @return void
     */
    public function copyBaseTheme()
    {
        //copyDirectory
    }  

}
