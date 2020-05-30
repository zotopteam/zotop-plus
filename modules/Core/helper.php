<?php
if (!function_exists('allow')) {

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

if (!function_exists('array_deep')) {
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

if (!function_exists('array_object')) {

    /**
     * 数组转对象
     * 
     * @param  array $array 数组
     * @return mixed
     */
    function array_object($array)
    {
        if (is_array($array)) {
            return (object) array_map(__FUNCTION__, $array);
        }

        return $array;
    }
}

if (!function_exists('object_array')) {

    /**
     * 对象转数组
     * 
     * @param  array $array 数组
     * @return mixed
     */
    function object_array($object)
    {
        if (is_object($object)) {
            $object = array_map(__FUNCTION__, (array) $object);
        }

        return $object;
    }
}

if (!function_exists('str_array')) {

    /**
     * 分隔字符串为数组，支持多个分隔符
     * 
     * @param array $array 数组
     * @param array delimiters 分隔符
     * @return mixed
     */
    function str_array($string, ...$delimiters)
    {
        if (is_array($string)) {
            return $string;
        }

        if (blank($string)) {
            return [];
        }

        $array = explode(array_shift($delimiters), $string);

        if ($delimiters) {
            foreach ($array as $key => $val) {
                $array[$key] = str_array($val, ...$delimiters);
            }
        }

        return $array;
    }
}

if (!function_exists('array_merge_deep')) {
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

if (!function_exists('array_nest')) {

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
    function array_nest(array $array, $rootId = 0, $rootDepth = 0, $id = 'id', $parentid = 'parent_id', $children = 'children', $depth = "depth")
    {

        $nestArray = [];
        foreach ($array as $row) {
            if (is_array($row) && isset($row[$id]) && isset($row[$parentid]) && $row[$parentid] == $rootId) {
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

if (!function_exists('array_change_key')) {

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



if (!function_exists('image')) {

    /**
     * 根据图片Url获取图片访问完整路径或者缩略图路径
     * 
     * @param  string $path 图片路径
     * @param  int $width 图片宽度
     * @param  int $height 图片高度
     * @return string 临时图片URL
     */
    function image($url, $width = null, $height = null, $fit = true)
    {
        $path = public_path($url);

        // 如果图片不存在
        if (empty($url) || !File::exists($path)) {
            $url  = app('themes')->asset('img/empty.jpg');
            $path = app('themes')->path('assets/img/empty.jpg');
        }

        // 如果缩放图片
        if ($width || $height) {
            $url = md5($url);
            $url = 'thumbnails/' . substr($url, 0, 2) . '/' . substr($url, 2, 2) . '/' . $url . '-' . intval($width) . '-' . intval($height) . '-' . intval($fit) . '.' . File::extension($path);
            $file = public_path($url);

            // 缩略图不存在，或者原图片被修改
            if (!File::exists($file) || File::lastModified($file) < File::lastModified($path)) {

                // 如果目录不存在，尝试创建
                if (!File::isDirectory($dir = dirname($file))) {
                    File::makeDirectory($dir, 0775, true);
                }

                // 拷贝图片到临时目录
                File::copy($path, $file);

                // 图片处理
                if ($width && $height && $fit) {
                    app('image')->make($file)->fit($width, $height)->save();
                } else {
                    app('image')->make($file)->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save();
                }
            }
        }

        return url($url);
    }
}
