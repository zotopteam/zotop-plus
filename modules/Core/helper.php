<?php
if (! function_exists('preview')) {
    /**
     * 预览图片
     * 
     * @param  string $path 图片路径
     * @param  int $width 图片宽度
     * @param  int $height 图片高度
     * @return string 临时图片URL
     */
    function preview($path, $width=null, $height=null, $resize=true)
    {
        if ( empty($path) || !File::exists($path) ) {
            $path = app('current.theme')->path.'/assets/img/empty.png';
        }

        $temp = 'temp/preview/'.md5($path.'-'.$width.'-'.$height).'.'.File::extension($path);
        $file = public_path($temp);

        // 预览图片不存在，或者原图片被修改
        if ( !File::exists($temp) || File::lastModified($temp) < File::lastModified($path) ) {
            // 拷贝图片到临时目录
            File::copy($path, $file);
            // 图片缩放
            if ($width || $height) {
                if ($resize) {
                    app('image')->make($file)->resize($width,$height)->save();
                } else {
                    app('image')->make($file)->fit($width,$height)->save();
                }               
            }          
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
                    $module->icon = preview($module->icon,48,48);
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

if (! function_exists('path_base')) {
    /**
     * 将完整路径转化为base路径，base_path的反向函数
     * 
     * @param  string $path 路径
     * @return string 转换后路径
     */
    function path_base($path)
    {
        $path = str_after($path, app()->basePath());
        $path = str_replace('\\', '/', $path);
        return $path;
    }
}
