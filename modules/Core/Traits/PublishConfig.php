<?php

namespace Modules\Core\Traits;

trait PublishConfig
{
    /**
     * 发布并将模块配置合并到全局，以便用config()获取
     * 
     * @param string $module
     * @param string $fileName 不含后缀
     */
    public function publishConfig($module, $fileName)
    {
        $this->mergeConfigFrom($this->getModuleConfigFilePath($module, $fileName), strtolower("$module.$fileName"));

        $this->publishes([
            $this->getModuleConfigFilePath($module, $fileName) => config_path(strtolower("$module/$fileName.php")),
        ], 'config');
    }

    /**
     * Get path of the give file name in the given module
     * 
     * @param string $module
     * @param string $file
     * @return string
     */
    private function getModuleConfigFilePath($module, $fileName)
    {
        return $this->getModulePath($module) . "/Config/$fileName.php";
    }

    /**
     * @param $module
     * @return string
     */
    private function getModulePath($module)
    {
        //return base_path('Modules' . DIRECTORY_SEPARATOR . ucfirst($module));
        
        return \Module::getModulePath($module);
    }
}
