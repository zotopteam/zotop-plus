<?php
namespace Modules\Core\Support;


class Theme
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    protected $themes = [];

    protected $active = null;


    /**
     * @param $app  \Illuminate\Contracts\Foundation\Application
     */
    public function __construct($app)
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
        if ($type == null) return $this->themes;

        $list = [];

        foreach ($this->themes as $key => $theme) {

            // 只取当前类型的主题
            if ($theme->type == $type) $list[$key] = $theme;
        }

        return $list;
    }

    /**
     * 获取主题的 assets 路径 (公开目录).
     *
     * @param  string $name 主题名称
     * @return string
     */
    public function getAssetsPath($name='')
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
    public function getAssetsUrl($name='')
    {
        $base = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath($name));

        return $this->app['url']->asset($base);
    }    

    /**
     * active主题数据
     * 
     * @param  string $name      主题名称
     * @return mixed
     */
    public function active($name)
    {
        // 获取主题信息
        if ( isset($this->themes[$name]) ) {
            $this->active = $this->themes[$name];
        } else {
            throw new ThemeNotFoundException("Theme [{$name}] does not exist!");
        }

        return $this->active;
    }

    /**
     * 查找主题
     * 
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function find($name)
    {
        $theme = null;

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
    public function findOrFail($name)
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
     * @param string $asset
     *
     * @return string
     */
    public function asset($asset)
    {
        // 如过传入参数不包含主题名称，则使用当前名称
        if (strpos($asset,':') == false) {
            $asset = $this->active->name . ':' . $asset;         
        }

        // 分解参数
        list($name, $url) = explode(':', $asset);

        return $this->getAssetsUrl($name) . '/' . $url;
    }
}
