<?php

namespace App\Themes\Commands;

use App\Themes\Exceptions\FileExistedException;
use App\Themes\Exceptions\ThemeExistedException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:make
                            {theme : The name of theme will be created.}
                            {--type=frontend : The type of theme to be created. Optional values ​​are: frontend|backend|api}
                            {--force : Force the operation to run when it already exists.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme';


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
     * @return void
     * @throws \App\Themes\Exceptions\ThemeExistedException
     * @throws \Exception
     */
    public function handle()
    {
        // 覆盖模块
        if ($this->hasTheme()) {

            if (!$this->option('force')) {

                if ($this->laravel->runningInConsole()) {
                    $this->error('Theme ' . $this->getThemeStudlyName() . ' already exist!');
                    return;
                }

                throw new ThemeExistedException('Theme ' . $this->getThemeStudlyName() . ' already exist!', 1);

            }

            $this->laravel['files']->deleteDirectory($this->getThemePath());
        }

        // 创建文件
        $this->generateFiles();
        // 创建语言文件
        $this->generateLangs();
        // 复制文件
        $this->copyFiles();
        // 复制文件夹
        $this->copyDirectories();

        $this->info('Theme ' . $this->getThemeStudlyName() . ' created successfully!');
    }

    /**
     * 生成初始化文件
     *
     * @return void
     * @throws \Exception
     */
    public function generateFiles()
    {
        $files = $this->laravel['config']->get('themes.paths.files');

        foreach ($files as $stub => $path) {
            $this->generateStubFile($stub, $path, $this->option('force'));
        }
    }

    /**
     * 生成语言文件
     *
     * @return void
     * @throws \Exception
     */
    public function generateLangs()
    {
        $lang = $this->laravel['config']->get('app.locale');
        $path = $this->laravel['config']->get('themes.paths.dirs.lang');

        $this->generateStubFile('lang/lang.stub', $path . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'theme.php', $this->option('force'));
        $this->generateStubFile('lang/lang.json', $path . DIRECTORY_SEPARATOR . $lang . '.json', $this->option('force'));
    }

    /**
     * 复制缩略图
     *
     * @return void
     * @throws \Exception
     */
    public function copyFiles()
    {
        $sourcePath = $this->getStubPath('theme.jpg');
        $destinationPath = $this->getThemePath('theme.jpg');

        $this->laravel['files']->copy($sourcePath, $destinationPath);
        $this->info('Copied: ' . $destinationPath);
    }

    /**
     * 复制views和assets
     *
     * @throws \Exception
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function copyDirectories()
    {
        foreach (['assets', 'views'] as $dir) {
            $sourcePath = $this->getStubPath() . DIRECTORY_SEPARATOR . $dir;
            $destinationPath = $this->getThemePath($dir, true);

            $this->laravel['files']->copyDirectory($sourcePath, $destinationPath);
            $this->info('Copied: ' . $destinationPath);
        }
    }

    /**
     * 生成初始化目录
     *
     * @return void
     * @throws \Exception
     */
    public function generateDirs()
    {
        $dirs = $this->laravel['config']->get('themes.paths.dirs');

        foreach ($dirs as $key => $path) {
            $path = $this->getThemePath($path);

            if (!$this->laravel['files']->isDirectory($path)) {
                $this->laravel['files']->makeDirectory($path, 0755, true);
                $this->info('Created: ' . $path);
            }

            $this->generateGitKeep($path);
        }
    }

    /**
     * 检查主题是否存在
     *
     * @return boolean [description]
     * @throws \Exception
     */
    public function hasTheme()
    {
        if (file_exists($this->getThemePath('theme.json'))) {
            return true;
        }

        return false;
    }

    /**
     * 获取模块小写名称
     *
     * @return string
     */
    public function getThemeLowerName()
    {
        return strtolower($this->argument('theme'));
    }

    /**
     * 获取模块变种驼峰名称 foo_bar => FooBar
     *
     * @return string
     */
    public function getThemeStudlyName()
    {
        return Str::studly($this->argument('theme'));
    }

    /**
     * 获取输入的主题类型
     *
     * @return string
     */
    public function getTypeInput()
    {
        return strtolower($this->option('type'));
    }

    /**
     * 获取主题路径
     *
     * @param string|null $subpath 子路径，或者子路径key
     * @param boolean $isPath 是否为路径
     * @return string
     * @throws \Exception
     */
    public function getThemePath($subpath = null, $isPath = true)
    {
        $path = $this->getConfig('paths.themes') . DIRECTORY_SEPARATOR . $this->getThemeStudlyName();

        if (empty($subpath)) {
            return $path;
        }

        if ($isPath) {
            return $path . DIRECTORY_SEPARATOR . $subpath;
        }

        return $path . DIRECTORY_SEPARATOR . $this->getConfig("paths.dirs.{$subpath}");
    }

    /**
     * 获取主题全局配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfig($key = null)
    {
        if ($key) {
            $value = $this->laravel['config']->get("themes.{$key}");

            if (empty($value)) {
                throw new \Exception("config {$key} does not exist", 1);
            }

            return $value;
        }

        return $this->laravel['config']->get('themes');
    }

    /**
     * 获取stub路径
     *
     * @param string|null $stub stub name
     * @return string
     */
    public function getStubPath($stub = null)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Stubs';

        if ($stub) {

            //如果不包含扩展名，则扩展名为stub
            if (!Str::contains($stub, '.')) {
                $stub = $stub . '.stub';
            }

            return $path . DIRECTORY_SEPARATOR . $stub;
        }

        return $path;
    }

    /**
     * 获取模块内容
     *
     * @param string $stub stub name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getStubContent(string $stub)
    {
        $path = $this->getStubPath($stub);

        if (!$this->laravel['files']->exists($path)) {
            $this->warn('Unknown: ' . $path);
            return null;
        }

        return $this->laravel['files']->get($path);
    }

    /**
     * 渲染stub
     *
     * @param string $stub
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function renderStub(string $stub)
    {
        $content = $this->getStubContent($stub);

        $replaces = [
            'theme_studly_name' => $this->getThemeStudlyName(),
            'theme_lower_name'  => $this->getThemeLowerName(),
            'theme_type'        => $this->getTypeInput(),
        ];

        foreach ($replaces as $search => $replace) {
            $content = str_replace('$' . strtoupper($search) . '$', $replace, $content);
        }

        return $content;
    }

    /**
     * 生成文件
     *
     * @param string $stub 不含文件后缀则自动补充.stub
     * @param string $path 相对模块的路径
     * @param bool $force
     * @return string
     * @throws \Exception
     */
    public function generateStubFile(string $stub, string $path, $force = false)
    {
        $path = $this->getThemePath($path);

        if (!$force && $this->laravel['files']->exists($path)) {

            if ($this->laravel->runningInConsole()) {
                $this->warn('Existed: ' . $path);
                return false;
            }

            throw new FileExistedException('Existed: ' . $path, 1);
        }

        if ($content = $this->renderStub($stub)) {

            // 自动创建不存在的目录
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }

            // 替换json文件中的斜线为双斜线
            if (Str::endsWith($path, '.json')) {
                $content = str_replace('\\', '\\\\', $content);
            }

            $this->laravel['files']->put($path, $content);

            $this->info('Created: ' . $path);
            return true;
        }

        return false;
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function generateGitKeep(string $path)
    {
        $this->laravel['files']->put($path . DIRECTORY_SEPARATOR . '.gitkeep', 'git keep');
    }
}
