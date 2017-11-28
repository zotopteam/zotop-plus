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
     * Get the permissions from all the enabled modules
     * @return array
     */
    public function all()
    {
        $permissions = [];

        foreach ($this->module->enabled() as $moduleEnabled) {
            $module     = $moduleEnabled->getLowerName();
            $permission = $moduleEnabled->getFileData('permission.php');

            array_set($permissions, $module, [
                'key'         => $module,
                'title'       => trans($moduleEnabled->title),
                'permissions' => []
            ]);

            foreach ($permission as $controller => $actions) {
                // controller
                array_set($permissions, $module.'.permissions.'.$controller, [
                    'key'         => $module.'.'.$controller,
                    'title'       => trans($module.'::'.$controller.'.title'),
                    'permissions' => []
                ]);

                 // action 
                foreach ($actions as $action) {
                    array_set($permissions, $module.'.permissions.'.$controller.'.permissions.'.$action, [
                        'key'        => $module.'.'.$controller.'.'.$action,
                        'title'      => trans($module.'::'.$controller.'.'.$action),
                    ]);
                }
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
