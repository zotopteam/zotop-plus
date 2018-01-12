<?php
if (! function_exists('allow')) {

    /**
     * 检查当前用户是否拥有权限
     * @param  $permission 权限节点
     * @return bool
     */
    function allow($permission)
    {
        return auth()->user()->allow($permission);
    }
}

if (! function_exists('array_deep')) {
    /**
     * 将array_dot得到的数组反向为真实数组格式
     * @param  array  $arrayDot [description]
     * @return [type]           [description]
     */
    function array_deep(array $arrayDot)
    {
        $array = array();
        foreach ($arrayDot as $key => $value) {
            array_set($array, $key, $value);
        }
        return $array;  
    }
}

if (! function_exists('array_object')) {
    
    /**
     * 数组转对象
     * @param  array $array 数组
     * @return mixed
     */
    function array_object($array)
    {
        if (is_array($array)) {
            return (object)array_map(__FUNCTION__, $array);
        } else {
            return $array;
        }
    }
}

if (! function_exists('array_merge_deep')) {
    /**
     * 深层次合并数组
     * @param  array  $arrayDot [description]
     * @return array
     */
    function array_merge_deep(...$args)
    {
        $arrays = func_get_args();
        $return = array_shift($arrays);

        foreach ($arrays as $array) {
            reset($return); //important
            while (list($key, $value) = @each($array)) {
                if (is_array($value) && @is_array($return[$key])) {
                    $return[$key] = array_merge_deep($return[$key], $value);
                } else {
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }
}

if (! function_exists('array_nest')) {

    /**
     * 将扁平并含有(id,parent_id)的数组转化为嵌套数组，并追加深度
     * 
     * @code php
     * $data = array(
     *     array('id' => 1, 'parent_id' => 0, 'name' => '1-0'),
     *     array('id' => 5, 'parent_id' => 0, 'name' => '5-0'),
     *     array('id' => 2, 'parent_id' => 1, 'name' => '2-1'),
     *     array('id' => 3, 'parent_id' => 1, 'name' => '2-1'),
     *     array('id' => 4, 'parent_id' => 3, 'name' => '4-3'),   
     *     array('id' => 6, 'parent_id' => 5, 'name' => '6-5'),
     * );
     *
     * $values = array_nest($data, 0);
     * @endcode
     * 
     * @param  array $array   扁平数组
     * @param  string $rootId  初始节点编号
     * @param  string $rootDepth 初始节点深度
     * @param  string $id   id的键名
     * @param  string $parentid parent_id的键名
     * @param  string $children  转化后的子数据键名
     * @param  string $depth  转化后的节点深度
     * @return array
     */
    function array_nest(array $array, $rootId=0, $rootDepth=0, $id='id', $parentid='parent_id', $children='children', $depth="depth")
    {

        $nestArray = [];
        foreach ($array as $row) {
            if(is_array($row) && isset($row[$id]) && isset($row[$parentid]) && $row[$parentid] == $rootId) {
                $row[$depth] = $rootDepth;
                if ($nest = array_nest($array, $row[$id], $row[$depth] + 1, $id, $parentid, $children, $depth)) {
                    $row[$children] = $nest;
                }
                $nestArray[] = $row;
            }
        }
        
        return $nestArray;
    }
}

if (! function_exists('array_change_key')) {

    /**
     * 改变数组键名
     * 
     * @param  array  $array   源数组
     * @param  array  $changes 数组键名映射
     * @return array
     */
    function array_change_key(array $array, array $changes)
    {
        foreach ($changes as $old => $new) {            
            array_set($array, $new, array_pull($array, $old));
        }

        return $array;
    }
}

if (! function_exists('preview')) {
    /**
     * 预览图片
     * 
     * @param  string $path 图片路径
     * @param  int $width 图片宽度
     * @param  int $height 图片高度
     * @return string 临时图片URL
     */
    function preview($path, $width=null, $height=null, $fit=true)
    {
        if ( empty($path) || !File::exists($path) ) {
            $path = app('current.theme')->path.'/assets/img/empty.jpg';
        }

        $temp = md5($path);
        $temp = substr($temp, 0, 2).'/'.substr($temp, 2, 2).'/'.$temp;
        $temp = 'previews/'.$temp.'/'.$width.'-'.$height.'.'.File::extension($path);
        $file = public_path($temp);

        // 预览图片不存在，或者原图片被修改
        if ( !File::exists($file) || File::lastModified($file) < File::lastModified($path) ) {           
            // 如果目录不存在，尝试创建
            if (!File::isDirectory($dir = dirname($file))) {
                File::makeDirectory($dir, 0775, true);
            }
            // 拷贝图片到临时目录
            File::copy($path, $file);
            // 图片缩放
            if ($width || $height) {
                if ($fit) {
                    app('image')->make($file)->fit($width, $height)->save();
                } else {
                    app('image')->make($file)->resize($width, $height, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save();
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

if (! function_exists('trans_has')) {
    /**
     * 检查是否存在对应翻译
     * 
     * @param  string $path 路径
     * @return string 转换后路径
     */
    function trans_has($key, $locale = null, $fallback = true)
    {
        return app('translator')->has($key, $locale, $fallback);
    }
}

if (! function_exists('trans_find')) {
    /**
     * 翻译文件，可以从多个key中插座，没有找到翻译则结果返回空
     *
     * @param  string|array  $keys 如果是字符串，多个用||分割
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function trans_find($keys, $replace = [], $locale = null)
    {
        if (is_string($keys)) {
            $keys = explode('||', $keys);
        }

        foreach ($keys as $key) {
            if (trans_has($key, $locale, false)) {
                return trans($key, $replace, $locale);
            }
        }

        return null;
    }
}
