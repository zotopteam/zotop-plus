<?php

use App\Support\ImagePreview;
use Illuminate\Support\Str;

if (!function_exists('array_object')) {

    /**
     * 数组转对象
     *
     * @param array|string $array
     * @return array|object
     * @author Chen Lei
     * @date 2020-11-17
     */
    function array_object($array)
    {
        if (is_array($array)) {
            return (object)array_map(__FUNCTION__, $array);
        }

        return $array;
    }
}

if (!function_exists('object_array')) {

    /**
     * 对象转数组
     *
     * @param $object
     * @return array
     * @author Chen Lei
     * @date 2020-11-17
     */
    function object_array($object)
    {
        if (is_object($object)) {
            $object = array_map(__FUNCTION__, (array)$object);
        }

        return $object;
    }
}

if (!function_exists('str_array')) {

    /**
     * 分隔字符串为数组，支持多个分隔符
     *
     * <code>
     * str_array('a:1;b:2:c:3', ';', ':')
     * </code>
     *
     * @param mixed $string
     * @param mixed ...$delimiters
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
     * @param array $array 扁平数组
     * @param string|int $rootId 初始节点编号
     * @param string|int $rootDepth 初始节点深度
     * @param string $id id的键名
     * @param string $parentId parent_id的键名
     * @param string $children 转化后的子数据键名
     * @param string $depth 转化后的节点深度
     * @return array
     */
    function array_nest(array $array, $rootId = 0, $rootDepth = 0, $id = 'id', $parentId = 'parent_id', $children = 'children', $depth = "depth")
    {

        $nestArray = [];
        foreach ($array as $row) {
            if (is_array($row) && isset($row[$id]) && isset($row[$parentId]) && $row[$parentId] == $rootId) {
                $row[$depth] = $rootDepth;
                if ($nest = array_nest($array, $row[$id], $row[$depth] + 1, $id, $parentId, $children, $depth)) {
                    $row[$children] = $nest;
                }
                $nestArray[] = $row;
            }
        }

        return $nestArray;
    }
}

if (!function_exists('dirsize')) {

    /**
     * 递归获取目录大小
     *
     * @param string $dir dir path
     * @param boolean $format 是否格式化为可读格式
     * @return string
     */
    // 递归计算文件夹大小
    function dirsize(string $dir, $format = true)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : dirsize($each, false);
        }
        return $format ? size_format($size) : $size;
    }
}

if (!function_exists('size_format')) {

    /**
     * 格式化size为可读格式
     *
     * @param integer $bytes size bytes
     * @param integer $decimals 小数位个数
     * @return string
     */
    function size_format(int $bytes, int $decimals = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;
        while ($bytes >= 1024) {
            $bytes /= 1024;
            $index++;
        }
        return number_format($bytes, $decimals) . ' ' . $units[$index];
    }
}

if (!function_exists('path_base')) {
    /**
     *
     * 将完整路径转化为base路径，base_path的反向函数,前后均不包含斜杠
     *
     * @param string $path 路径
     * @return string 转换后路径
     */
    function path_base(string $path)
    {
        $path = Str::replaceFirst(base_path(), '', $path);
        $path = str_replace('\\', '/', $path);
        $path = trim($path, '/');
        return $path;
    }
}

if (!function_exists('trans_has')) {
    /**
     * 检查是否存在对应翻译
     *
     * @param string $key
     * @param string|null $locale
     * @param bool $fallback
     * @return bool
     */
    function trans_has(string $key, $locale = null, $fallback = true)
    {
        return app('translator')->has($key, $locale, $fallback);
    }
}

if (!function_exists('trans_find')) {
    /**
     * 翻译文件，可以从多个key中插座，没有找到翻译则结果返回空
     *
     * @param string|array $keys 如果是字符串，多个用||分割
     * @param array $replace
     * @param string|null $locale
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

if (!function_exists('module')) {
    /**
     * 获取module
     *
     * @param string|null $name
     * @return mixed
     */
    function module($name = null)
    {
        if (is_null($name)) {
            return app('modules');
        }

        return app('modules')->findOrFail($name);
    }
}

if (!function_exists('attribute')) {

    /**
     * make a attribute
     *
     * @param array $attribute
     * @return \App\Support\Attribute
     * @author Chen Lei
     * @date 2020-12-05
     */
    function attribute($attribute = [])
    {
        return new \App\Support\Attribute($attribute);
    }
}

if (!function_exists('preview')) {

    /**
     * 根据图片路径，预览站点内任意位置的图片
     *
     * @param string $path 图片路径 支持绝对路径和存储盘路径，public:uploads/abc.png
     * @param int|null $width 图片宽度
     * @param int|null $height 图片高度
     * @param string $filter fit=适应 resize=缩放
     * @return string 预览地址
     */
    function preview(string $path, $width = null, $height = null, $filter = 'resize')
    {
        // 生成文件实例
        $preview = ImagePreview::file($path);

        // 如果是预览原图，因为原图一般都比较大，所以直接生成动态访问地址
        if (empty($width)) {
            return $preview->dynamicUrl();
        }

        return $preview->width($width)->height($height)->filter($filter)->url();
    }
}
