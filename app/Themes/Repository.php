<?php

namespace App\Themes;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class Repository
{
    use Macroable, ForwardsCalls {
        Macroable::__call as macroCall;
    }

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * 当前启用的主题
     *
     * @var [type]
     */
    protected $theme;

    /**
     * __construct
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 全部主题
     *
     * @return array
     */
    public function all()
    {
        static $themes = [];

        if (empty($themes)) {

            $path = $this->app['config']->get('themes.paths.themes');
            $path = Str::endsWith($path, '/*') ? $path : Str::finish($path, '/*');

            $manifests = $this->app['files']->glob("{$path}/theme.json");

            foreach ($manifests as $manifest) {
                $theme = new Theme($this->app, $manifest);
                if ($key = $theme->getLowerName()) {
                    $themes[$key] = $theme;
                }
            }

        }

        return $themes;
    }

    /**
     * 获取特定类型的主题
     *
     * @param string $type 主题类型：admin,front,api
     * @return array
     */
    public function type(string $type)
    {
        $themes = $this->all();

        foreach ($themes as $key => $theme) {
            if (strtolower($type) != $theme->getType()) {
                unset($themes[$key]);
            }
        }

        return $themes;
    }

    /**
     * 按照名称获取主题
     *
     * @param string $name 主题名称
     * @return theme
     */
    public function find(string $name)
    {
        return Arr::get($this->all(), strtolower($name));
    }

    /**
     * Find a specific theme, if there return that, otherwise throw exception.
     *
     * @param $name
     * @return Theme
     * @throws NotFoundException
     */
    public function findOrFail($name)
    {
        $theme = $this->find($name);

        if ($theme !== null) {
            return $theme;
        }

        throw new NotFoundException("Theme [{$name}] does not exist!");
    }

    /**
     * 启用主题
     *
     * @param $theme
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function active($theme)
    {
        if ($this->theme = $this->find($theme)) {
            $this->theme->active();
        }
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        // 调用theme方法，asset,getName,getTitle……
        return call_user_func_array([$this->theme, $method], $parameters);
    }
}
