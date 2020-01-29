<?php
namespace Modules\Core\Support;

use Illuminate\Contracts\Foundation\Application;

class Permission
{
    /**
     * @var App
     */    
    protected $app;

    /**
     * init
     */
    public function __construct(Application $app)
    {
        $this->app  = $app;
    }

    /**
     * Get all permissions from modules
     * 
     * @return array
     */
    public function all()
    {
        $permissions = [];

        // 获取所有启用的模块权限
        foreach ($this->app['modules']->enabled() as $module) {
            
            $permission = [];

            // 从权限文件获取权限设置数据
            $path = $module->getPath('permission.php');

            if ($this->app['files']->exists($path)) {
                $permission = require $path;
            }

            // 无权限则不显示
            if ($permission && is_array($permission)) {
                $name = $module->getLowerName();
                $permissions[$name] = [
                    'title'       => $module->getTitle(),
                    'description' => $module->getDescription(),
                    'permissions' => $permission
                ];
            }     
        }

        return $permissions;
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }    
}
