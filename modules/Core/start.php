<?php

use App\Modules\Facades\Module;
use App\Support\Facades\Filter;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * 开始菜单
 */
Filter::listen('global.start', 'Modules\Core\Hooks\Hook@start', 99999);

/**
 * 快捷导航
 */
Filter::listen('global.navbar', 'Modules\Core\Hooks\Hook@navbar', 1);

/**
 * 快捷工具
 */
Filter::listen('global.tools', 'Modules\Core\Hooks\Hook@tools');

/**
 * 全局js变量
 */
Filter::listen('window.cms', 'Modules\Core\Hooks\Hook@windowCms');

/**
 * 模块管理
 */
Filter::listen('module.manage', 'Modules\Core\Hooks\Hook@moduleManage');
Filter::listen('module.manage', 'Modules\Core\Hooks\Hook@moduleManageCore');

/**
 * 扩展 Request::referer 功能
 * 返回页面的来源
 */
Request::macro('referer', function () {

    // 如果前面有传入，比如表单传入，直接返回传入值
    if ($referer = $this->input('_referer')) {
        return $referer;
    }

    // 如果有存储值，取出存储值
    if ($this->session()->has('_referer')) {
        return $this->session()->pull('_referer');
    }

    // 返回前面的值
    return URL::previous();
});

/**
 * 扩展 Request::remember 功能
 * 键值存在，则存储键值，如果传入参数的键值不存在，则返回上一次存储的键值
 */
Request::macro('remember', function ($key, $default = null, $minutes = 365 * 24 * 60) {

    // 从路由或者参数中获取键值
    $var = $this->route($key) ?? $this->input($key);

    // 每个页面存储的数据独立
    $key = $this->route()->uri() . '_' . $key;

    // 如果键值存在，存储并返回键值
    if (!is_null($var)) {
        Cookie::queue($key, $var, $minutes);
        return $var;
    }

    // 如果键值不存在，则返回上一次存储的键值
    return $this->cookie($key) ?? value($default);
});


/**
 * 扩展 Route:active 如果是当前route，则返回 active
 */
Router::macro('active', function ($route, $active = "active", $normal = '') {
    return Route::is($route) ? $active : $normal;
});

/**
 * 扩展File::mime方法, 获取文件类型audio/avi，text/xml 斜杠前面部分
 */
File::macro('mime', function ($file) {
    if ($mimeType = static::mimeType($file)) {
        return Str::before($mimeType, '/');
    }
    return null;
});

/**
 * 扩展File::humanType方法, 获取文件的类型，依据系统可上传的类型判断
 */
File::macro('humanType', function ($file) {
    $extension = strpos($file, '.') ? static::extension($file) : trim($file, '.');
    $humanTypes = config('core.upload.types');
    foreach ($humanTypes as $type => $info) {
        $extensions = explode(',', strtolower($info['extensions']));
        if (in_array($extension, $extensions)) {
            return $type;
        }
    }
    return null;
});

/**
 * 扩展File::icon方法, 获取文件图标
 */
File::macro('icon', function (...$file) {
    $icon = Module::data('core::file.icon');
    foreach (func_get_args() as $file) {
        $extension = strpos($file, '.') ? static::extension($file) : trim($file, '.');
        if (isset($icon[$extension])) {
            return $icon[$extension];
        }
    }
    return 'fa fa-file';
});

/**
 * 扩展File::meta, 从文件头部的注释中获取文件文本文件的meta说明信息
 */
File::macro('meta', function ($file, array $headers = []) {

    // 获取的header
    $headers = $headers ? $headers : ['title', 'description', 'author', 'url'];

    // 读取文件的头部8KB
    $fp = fopen($file, 'r');
    $data = fread($fp, 8192);
    fclose($fp);

    // 从注释中获取meta
    foreach ($headers as $key) {
        preg_match('/{{--\s*' . $key . ':(.*?)\s*--}}/s', $data, $match);
        ${$key} = $match ? trim($match[1]) : '';
    }

    return compact($headers);
});

/**
 * 扩展上传的获取文件类型，依据系统可上传的类型判断
 */
UploadedFile::macro('getHumanType', function () {
    $extension = $this->getClientOriginalExtension();
    return File::humanType($extension);
});

/**
 * 扩展上传的获取文件的hash（md5)值
 */
UploadedFile::macro('getHash', function () {
    $realpath = $this->getRealPath();
    return md5_file($realpath);
});

/**
 * 扩展查询器 whereSmart, 当查询条件为字符串时自动转化为数组，当数组有多个值时，使用whereIn查询，当数组只有一个值时，使用where查询
 * whereSmart('type', 'aaa,bbb')
 * whereSmart('type', ['aaa','bbb'])
 */
Builder::macro('whereSmart', function ($column, $param, $separator = ',') {

    return $this->when(!empty($param), function ($query) use ($column, $param, $separator) {

        $param = is_array($param) ? array_values($param) : explode($separator, $param);

        if (count($param) == 1) {
            return $query->where($column, reset($param));
        }

        return $query->whereIn($column, $param);
    });
});

/**
 * 扩展查询器 searchIn, 搜索多个字段
 * searchIn('title,summary', 'keyword')
 * searchIn(['title','summary'], 'keyword')
 */
Builder::macro('searchIn', function ($column, $param, $separator = ',') {

    return $this->when(!empty($param), function ($query) use ($column, $param, $separator) {

        $columns = is_array($column) ? array_values($column) : explode($separator, $column);

        return $this->where(function ($query) use ($columns, $param) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$param}%");
            }
        });
    });
});

