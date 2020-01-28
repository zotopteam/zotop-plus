<?php
namespace Modules\Core\Support;

class Permission
{
    /**
     * @var Module
     */    
    protected $module;

    /**
     * init
     */
    public function __construct()
    {
        $this->module  = app('modules');
    }

    /**
     * Get the permissions from modules
     * @return array
     */
    public function all()
    {
        $permissions = [];

        foreach ($this->module->enabled() as $module) {
            $name     = $module->getLowerName();
            $permission = $module->getFileData('permission.php');

            // 模块未开启权限不显示
            if (empty($permission)) {
                continue;
            }

            $permissions[$name] = [
                'title'       => trans($module->title),
                'description' => trans($module->description),
                'permissions' => $permission
            ];       
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
