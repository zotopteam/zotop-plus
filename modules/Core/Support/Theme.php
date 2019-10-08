<?php
namespace Modules\Core\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;

class Theme
{
    use Macroable;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    protected $themes = [];

    /**
     * @param $app  \Illuminate\Contracts\Foundation\Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 注册主题
     * @param  string $path 主题路径
     * @return void
     */
    public function registerPath($path)
    {
        $realPath = realpath($path);

        $jsonPath = $realPath.'/theme.json';

        if ($this->app['files']->exists($jsonPath)) {

            $json = json_decode($this->app['files']->get($jsonPath));
            
            if ($json and isset($json->name)) {

                // 主题路径信息
                $json->path  = $realPath;
                $json->asset = $this->getAssetsUrl($json->name);

                // 存入主题数组
                $this->themes[strtolower($json->name)] = $json;
            }        
        }
    }


    /**
     * 获取主题列表
     * 
     * @param  string $type 类型
     * @return array
     */
    public function getList($type=null)
    {
        // 如果type为空，返回全部的主题
        if ($type == null) {
            return $this->themes;
        }

        // 只取当前类型的主题
        $list = [];

        foreach ($this->themes as $key => $theme) {
            if ($theme->type == $type) {
                $list[$key] = $theme;
            }
        }

        return $list;
    }

    /**
     * 获取主题的 assets 路径 (公开目录).
     *
     * @param  string $name 主题名称
     * @return string
     */
    public function getAssetsPath($name=null)
    {
        $assetsPath = $this->app['config']->get('themes.paths.assets', public_path('themes'));

        if ($name) {
            $assetsPath =  $assetsPath. '/' . $name;
        }

        return $assetsPath;
    }

    /**
     * 获取主题的assets可访问路径(公开目录)
     * 
     * @param  string $name 主题名称
     * @return string
     */
    public function getAssetsUrl($name=null)
    {
        $base = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath($name));

        return $this->app['url']->asset($base);
    }

    /**
     * 查找主题
     * 
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function find($name=null)
    {
        $theme = null;
        $name = $name ?? $this->app['current.theme'];

        // 获取主题信息
        if ( isset($this->themes[$name]) ) {
            $theme = $this->themes[$name];
        }

        return $theme;
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return Module
     *
     * @throws ModuleNotFoundException
     */
    public function findOrFail($name=null)
    {
        $theme = $this->find($name);

        if ($theme !== null) {
            return $theme;
        }

        throw new ThemeNotFoundException("Theme [{$name}] does not exist!");
    }

    /**
     * 获取主题的 asset url，如：Theme::assets('[default:]test/shortcut.png')
     *
     * @param string $asset url
     * @param string $name theme name
     * @return string
     */
    public function asset($asset)
    {
        // 如过传入参数不包含主题名称，则使用当前名称，否则分解为主题和路径
        if (strpos($asset,':') == false) {
            $name = $this->app['current.theme'];
            $url  = $asset;    
        } else {
            list($name, $url) = explode(':', $asset);            
        }

        $theme = $this->find($name);
        $asset = $this->getAssetsUrl($name) . '/' . ltrim($url, '/');
        $asset = $asset .'?version='.$theme->version;

        return $asset;
    }

    /**
     * 获取主题的文件路径
     * 
     * @param string $path
     * @param string $name theme name
     * @return string
     */
    public function path($path=null)
    {
        if (strpos($path, ':') == false) {
            $name = $this->app['current.theme'];
        } else {
            list($name, $path) = explode(':', $path);
        }

        $path = ltrim($path, DIRECTORY_SEPARATOR);

        if ($path) {
            return $this->themes[$name]->path . DIRECTORY_SEPARATOR . $path;
        }

        return $this->themes[$name]->path;
    }

    /**
     * 获取主题信息
     * 
     * @param string $path
     * @param string $name theme name
     * @return string
     */
    public function get($key)
    {
        if (strpos($key, ':') == false) {
            $name = $this->app['current.theme'];
        } else {
            list($name, $key) = explode(':', $key);
        }

        return data_get($this->themes[$name], $key);
    }    

    /**
     * 启用主题
     * @param  string $name 主题名称
     * @return 
     */
    public function active($name=null)
    {
        $theme = $this->find($name);

        if (! $theme) {
            return;
        }

        //注册全局views路径，实现errors寻址
        $this->app['config']->set('view.paths', [
            $theme->path.'/views',
            resource_path('views'),
        ]);

        // 注册当前模块和主题的views，实现view在主题和模块中寻址
        foreach ($this->app['modules']->getOrdered() as $module) {
            $this->app['view']->addNamespace($module->getLowerName(), [
                $theme->path.'/views/'.$module->getLowerName(),
                $module->getPath() . '/Resources/views/'.$this->app['current.type']
            ]);      
        }

        // 注册语言
        $this->app['translator']->addJsonPath($theme->path.'/lang');
        $this->app['translator']->addNamespace('theme', $theme->path.'/lang');

        // 注册启动文件
        $files = isset($theme->files) && is_array($theme->files) ? $theme->files : [];
        foreach ($files as $file) {
            $file = $theme->path.'/'.$file;
            if (file_exists($file)) {
                require $file;
            }
        }

        // Hook
        $this->app['hook.action']->fire('theme.active', $theme);              
    }
}
