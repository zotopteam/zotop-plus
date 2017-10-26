<?php

if (! function_exists('preview')) {
    /**
     * 预览图片
     * 
     * @param  string $path 图片路径
     * @return string 临时图片URL
     */
    function preview($path)
    {
        if ( empty($path) || !File::exists($path) ) {
            return \Theme::asset(app('current.theme')->name.':img/placeholder.png');
        }

        $temp = 'temp/preview/'.md5($path).'.'.File::extension($path);
        $file = public_path($temp);

        // 预览图片不存在，或者原图片被修改
        if ( !File::exists($temp) OR File::lastModified($temp) < File::lastModified($path) ) {
            File::copy($path, $file);
        }  

        return url($temp);
    }
}

if (! function_exists('module')) {

    /**
     * 获取全部module或者某个module信息
     * 
     * @param  string $moduleName 模块名称
     * @return mixed 
     */
    function module($moduleName='')
    {
        static $modules = [];

        if (empty($modules)) {
           
            // 获取全部模块
            $modules = \Module::all();

            // 默认安装顺序排序
            $direction = 'asc';

            // 模块排序
            uasort($modules, function($a, $b) use ($direction) {

                if ($a->order == $b->order) {
                    return 0;
                }

                if ($direction == 'desc') {
                    return $a->order < $b->order ? 1 : -1;
                }

                return $a->order > $b->order ? 1 : -1;
            });   


            foreach ($modules as $name=>$module) {
                
                $namespace = strtolower($name);

                // 模块图标
                if (empty($module->icon)) {
                    $module->icon = $module->getExtraPath('/Resources/assets/module.png');
                    $module->icon = preview($module->icon);
                }

                // 加载未启用模块语言包
                if ( !$module->active ) {
                    app('translator')->addNamespace($namespace, $module->getPath() . '/Resources/lang');
                }

                // 标题和描述语言化
                $module->title       = trans($module->title);
                $module->description = trans($module->description);                   
            }
        }

        return $moduleName ? $modules[$moduleName] : $modules;        
    }
}