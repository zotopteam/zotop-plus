<?php

use App\Hook\Facades\Filter;
use App\Themes\Facades\Form;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;

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
 * 文件上传
 */
Filter::listen('core.file.upload', 'Modules\Core\Hooks\Hook@upload');

/**
 * 模块管理
 */
Filter::listen('module.manage', 'Modules\Core\Hooks\Hook@moduleManage');
Filter::listen('module.manage', 'Modules\Core\Hooks\Hook@moduleManageCore');

/**
 * 扩展 Request::referer 功能
 */
Request::macro('referer', function() {

    // 如果前面有传入，比如表单传入
    if ($referer = request()->input('_referer')) {
        return $referer;
    }
    
    return \URL::previous();
});

/**
 * 扩展 Route:active 如果是当前route，则返回 active
 */
Router::macro('active', function($route, $active="active", $normal='') {
    return Route::is($route) ? $active : $normal;
});

/**
 * 扩展File::mime方法, 获取文件类型audio/avi，text/xml 斜杠前面部分  
 */
File::macro('mime', function($file) {
    if ($mimeType = static::mimeType($file)) {
        list($mime, $type) = explode('/', $mimeType);
        return $mime;
    }
    return null;
});

/**
 * 扩展File::humanType方法, 获取文件的类型，依据系统可上传的类型判断
 */
File::macro('humanType', function($file) {
    $extension  = strpos($file, '.') ? static::extension($file) : trim($file, '.');
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
File::macro('icon', function(...$file) {
    $icon = \Module::data('core::file.icon');
    foreach(func_get_args() as $file) {
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
File::macro('meta', function($file, array $headers=[]) {
    
    // 获取的header
    $headers = $headers ? $headers : ['title', 'description', 'author', 'url'];

    // 读取文件的头部8KB
    $fp   = fopen($file, 'r');
    $data = fread($fp, 8192);
    fclose($fp);

    // 从注释中获取meta
    foreach ($headers as $key) {
        preg_match('/{{--\s*'.$key.':(.*?)\s*--}}/s', $data, $match);
        ${$key} = $match ? trim($match[1]) : '';        
    }

    return compact($headers);
});

/**
 * 扩展上传的获取文件类型，依据系统可上传的类型判断
 */
UploadedFile::macro('getHumanType',function() {
    $extension = $this->getClientOriginalExtension();
    return File::humanType($extension);
});

/**
 * 扩展查询器 whereSmart, 当查询条件为字符串时自动转化为数组，当数组有多个值时，使用whereIn查询，当数组只有一个值时，使用where查询
 * whereSmart('type', 'aaa,bbb')
 * whereSmart('type', ['aaa','bbb'])
 */
Builder::macro('whereSmart', function($column, $param, $separator=',') {

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
Builder::macro('searchIn', function($column, $param, $separator=',') {

    return $this->when(!empty($param), function ($query) use ($column, $param, $separator) {

        $columns = is_array($column) ? array_values($column) : explode($separator, $column);

        return $this->where(function ($query) use ($columns, $param) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$param}%");
            }
        });
    });
    
});


/**
 * 文件上传
 */
Form::macro('upload', function($attrs) {
    
    // 标签预处理
    $attrs = Filter::fire('core.field.upload.attrs', $attrs);

    //获取上传配置
    $types = collect(config('core.upload.types'));

    // 标签参数分解
    $id       = $this->getId($attrs);
    $value    = $this->getValue($attrs);
    $name     = $this->getName($attrs);
    

    // 上传和选择参数
    $filetype  = $this->getAttribute($attrs, 'filetype');

    $url       = $this->getAttribute($attrs, 'url', route('core.file.upload'));
    $allow     = $this->getAttribute($attrs, 'allow', $types->implode('extensions',','));
    $maxsize   = $this->getAttribute($attrs, 'maxsize', 1024);
    $typename  = $this->getAttribute($attrs, 'typename', trans('core::file.type.files'));
    $folder    = $this->getAttribute($attrs, 'folder', '');
    $source_id = $this->getAttribute($attrs, 'source_id', $this->getValue($attrs));
    
    // 界面文字和图标
    $select_text = $this->getAttribute($attrs, 'select_text', trans('core::field.upload.select', [$typename])); 
    $button_icon = $this->getAttribute($attrs, 'button_icon', 'fa-upload');
    $button_text = $this->getAttribute($attrs, 'button_text', trans('core::field.upload.button', [$typename]));

    //选择个数
    $select = $this->getAttribute($attrs, 'select', 1);

    // 附加参数
    $params = $this->getAttribute($attrs, 'params',  [
        'select'     => $select,
        'type'       => $filetype ?: '',
        'typename'   => $typename,        
        'extensions' => $allow,
        'maxsize'    => $maxsize,
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => $name,
        'folder'     => $folder ?: '',
        'source_id'  => $source_id ?: '',
        'user_id'    => Auth::user()->id,
        'token'      => Auth::user()->token
    ]);

    // 选项
    $options = $this->getAttribute($attrs, 'options',  [
        'url'              => $url,
        'chunk_size'       => config('core.upload.chunk_size', '2mb'),
        'multipart_params' => $params,
        'filters'          => [
            'max_file_size'      => $maxsize.'mb',
            'mime_types'         => [['title'=>$select_text, 'extensions'=>$allow]],
            'prevent_duplicates' => true,
        ] 
    ]);

    // 高级上传及工具
    $tools = $this->getAttribute($attrs, 'tools', Module::data('core::field.upload.tools', $params));

    // 获取视图
    $view = $this->getAttribute($attrs, 'view', 'core::field.upload');

    return $this->toHtmlString(
            $this->view->make($view)
            ->with(compact('id', 'name', 'value', 'attrs', 'options', 'button_icon', 'button_text', 'select_text', 'tools'))
            ->render()
    );
});

/**
 * 单图片上传
 *
 * 
 * {field type="upload_image" value=""}
 * {field type="upload_image" value="" button="Upload" resize="['width'=>1920,height=>800,quality=>100,crop=>false]" params=>"[]"}
 */
Form::macro('upload_image', function($attrs) {
    
    // 标签预处理
    $attrs = Filter::fire('core.field.upload_image.attrs', $attrs);
    $attrs = $attrs + [
        'filetype'    => 'image',
        'typename'    => trans('core::file.type.image'),
        'button_icon' => 'fa-image',
        'allow'       => $this->getAttribute($attrs, 'allow', config('core.upload.types.image.extensions')),
        'preview'     => $this->getAttribute($attrs, 'preview', 'image')
    ];

    // 获取系统设置的默认压缩设置
    $resize = config('core.image.resize.enabled', true) ? [
        'width'   => config('core.image.resize.width', 1920),
        'height'  => config('core.image.resize.height', 1920),
        'quality' => config('core.image.resize.quality', 100),
        'crop'    => config('core.image.resize.crop',  false)
    ] : [];

    // 获取标签设置的resize
    // 1：如果未设置，则读取系统默认，传递给后端处理
    // 2：如果是数组（包含空数组），合并默认设置，传递给后端处理
    // 3：设置为true或者false，传递给后端进行处理
    $resize = $this->getAttribute($attrs, 'resize', $resize);

    // 水印设置
    $watermark = config('core.image.watermark');
    $watermark = $this->getAttribute($attrs, 'watermark', $watermark);

    //resize 设置传递给plupload
    $attrs['options'] = ['resize' => $resize];

    //resize 和 watermark 设置传给后端
    $attrs['params']  = ['resize' => $resize, 'watermark' => $watermark];

    return $this->macroCall('upload',  [$attrs]);
});

/**
 * 单图片上传
 *
 * 
 * {field type="gallery" value=""}
 * {field type="gallery" value="" watermark="true" resize="['width'=>1920,height=>800,quality=>100,crop=>false]" params=>"[]"}
 */
Form::macro('gallery', function($attrs) {
    
    $attrs['value']  = $this->getValue($attrs);
    $attrs['value']  = is_array($attrs['value']) ? array_values($attrs['value']) : [];
    $attrs['view']   = $attrs['view'] ?? 'core::field.gallery';
    $attrs['select'] = $attrs['select'] ?? 0;

    return $this->macroCall('upload_image',  [$attrs]);
});

/**
 * 日期选择器
 */
Form::macro('date', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getName($attrs);
    $icon  = $this->getAttribute($attrs, 'icon', false);

    $options = $this->getAttribute($attrs, 'options',  [
        'type'     => $this->getAttribute($attrs, 'type', 'date'),
        'position' => $this->getAttribute($attrs, 'position', 'absolute'),
        'format'   => $this->getAttribute($attrs, 'format', 'yyyy-MM-dd'),
        'lang'     => $this->getAttribute($attrs, 'lang', App::getLocale()), //TODO:语言问题由于laydate只支持cn和en，需要优化
        'min'      => $this->getAttribute($attrs, 'min', '1900-1-1'),
        'max'      => $this->getAttribute($attrs, 'max', '2099-12-31'),
        'range'    => $this->getAttribute($attrs, 'range', false),
        'theme'    => $this->getAttribute($attrs, 'theme', '#0072c6'),
        'btns'     => $this->getAttribute($attrs, 'btns', 'clear,now,confirm'),
        'trigger'  => $this->getAttribute($attrs, 'trigger', 'click'),
    ]);

    $options['elem']  = '#'.$id;
    $options['value'] = $value;
    $options['btns']  = $options['btns'] && is_string($options['btns']) ? explode(',' , $options['btns']) : ['confirm'];

    return $this->toHtmlString(
        $this->view->make('core::field.date')->with(compact('name', 'value', 'id', 'icon', 'attrs', 'options'))->render()
    );
});

/**
 * 日期时间选择器
 */
Form::macro('year', function($attrs) {
    $attrs['type']   = 'year';
    return $this->macroCall('date',  [$attrs]);
});

/**
 * 日期时间选择器
 */
Form::macro('month', function($attrs) {
    $attrs['type']   = 'month';
    return $this->macroCall('date',  [$attrs]);
});

/**
 * 日期时间选择器
 */
Form::macro('datetime', function($attrs) {
    $attrs['type']   = 'datetime';
    return $this->macroCall('date',  [$attrs]);
});

/**
 * 时间选择器
 */
Form::macro('time', function($attrs) {
    $attrs['type']   = 'time';
    return $this->macroCall('date',  [$attrs]);
});


/**
 * 单选组
 */
Form::macro('radiogroup', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getName($attrs);    
    $options = $this->getAttribute($attrs, 'options',  []);
    $column  = $this->getAttribute($attrs, 'column', 0);
    $class   = $this->getAttribute($attrs, 'class', 'radiogroup-default');
    // 如果没有选择值，选择options的第一个
    if (is_null($value)) {
        $value = array_keys($options);
        $value = reset($value);
    }
    return $this->toHtmlString(
        $this->view->make('core::field.radiogroup')->with(compact('name', 'value', 'column', 'options', 'class'))->render()
    );
});

/**
 * 单选卡片，支持图片和文字类型的卡片
 * 文字卡片 options = ['value'=>'show text']
 * 图片卡片 options = ['value'=>['img url']]
 * 图文卡片 options = ['value'=>['img url','show text']]
 */
Form::macro('radiocards', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getName($attrs);    
    $options = $this->getAttribute($attrs, 'options',  []);
    $column  = $this->getAttribute($attrs, 'column', 0);
    $class   = $this->getAttribute($attrs, 'class', 'radiocards-default');

    // 如果没有选择值，选择options的第一个
    if (is_null($value)) {
        $value = array_keys($options);
        $value = reset($value);
    }
    return $this->toHtmlString(
        $this->view->make('core::field.radiocards')->with(compact('name', 'value', 'column', 'options', 'class'))->render()
    );
});


/**
 * 是/否 开关
 */
Form::macro('toggle', function($attrs) {
    $value   = $this->getValue($attrs);
    $id      = $this->getId($attrs);
    $name    = $this->getName($attrs);

    $enable  = $this->getAttribute($attrs, 'enable', 1);
    $disable = $this->getAttribute($attrs, 'disable', 0);
    
    $value  = $value ?? $disable;
    
    $class   = $this->getAttribute($attrs, 'class');

    return $this->toHtmlString(
        $this->view->make('core::field.toggle')->with(compact('name', 'value', 'id', 'enable', 'disable', 'class'))->render()
    );
});

/**
 * 是/否
 */
Form::macro('bool', function($attrs) {
    // options
    $attrs['options'] = $this->getAttribute($attrs, 'options',  [
        1 => trans('master.yes'),
        0 => trans('master.no')
    ]);
    return $this->macroCall('radiogroup',  [$attrs]);
});

/**
 * 启用/禁用
 */
Form::macro('enable', function($attrs){
    // options
    $attrs['options'] = $this->getAttribute($attrs, 'options',  [
        1 => trans('master.enable'),
        0 => trans('master.disable')
    ]);
    return $this->macroCall('radiogroup',  [$attrs]);
});

/**
 * 多选组
 */
Form::macro('checkboxgroup', function($attrs){
    $value   = $this->getValue($attrs);
    $value   = is_array($value) ? $value : [];
    $name    = $this->getName($attrs);    
    $options = $this->getAttribute($attrs, 'options', []);
    $column  = $this->getAttribute($attrs, 'column', 0);
    $class   = $this->getAttribute($attrs, 'class', 'checkboxgroup-default');

    return $this->toHtmlString(
        $this->view->make('core::field.checkboxgroup')->with(compact('name', 'value', 'column', 'options', 'class', 'attrs'))->render()
    );
});

/**
 * 代码编辑器
 */
Form::macro('code', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getName($attrs);

    // 支持rows高度模式
    if ($rows  = $this->getAttribute($attrs, 'rows', 0)) {
        $attrs['height'] = $rows * 25;
    }

    $options = $this->getAttribute($attrs, 'options',  [
        'width'         => $this->getAttribute($attrs, 'width', '100%'),
        'height'        => $this->getAttribute($attrs, 'height', '500'),
        'placeholder'   => $this->getAttribute($attrs, 'placeholder', 'coding……'),
        'mode'          => $this->getAttribute($attrs, 'mode', 'text/html'),
        'watch'         => $this->getAttribute($attrs, 'watch', false),
        'toolbar'       => $this->getAttribute($attrs, 'toolbar', false),
        'codeFold'      => $this->getAttribute($attrs, 'codeFold', true),
        'searchReplace' => $this->getAttribute($attrs, 'searchReplace', true),
        'theme'         => $this->getAttribute($attrs, 'theme','default'),
        'path'          => \Module::asset('core:editormd/lib', false).'/',
    ]);

    if ($options['height'] == 'auto') {
        $options['autoHeight'] = true;
    }

    return $this->toHtmlString(
        $this->view->make('core::field.code')->with(compact('name', 'value', 'options'))->render()
    );
});

/**
 * markdown编辑器
 */
Form::macro('markdown', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getName($attrs);

    $options = $this->getAttribute($attrs, 'options',  [
        'width'              => $this->getAttribute($attrs, 'width', '100%'),
        'height'             => $this->getAttribute($attrs, 'height', '500'),
        'placeholder'        => $this->getAttribute($attrs, 'placeholder', 'content……'),
        'toolbar'            => $this->getAttribute($attrs, 'toolbar', true),
        'codeFold'           => $this->getAttribute($attrs, 'codeFold', true),
        'saveHTMLToTextarea' => $this->getAttribute($attrs, 'saveHTMLToTextarea', true),
        'htmlDecode'         => $this->getAttribute($attrs, 'htmlDecode', 'style,script,iframe|on*'),
        'theme'              => $this->getAttribute($attrs, 'theme','default'),
        'path'               => \Module::asset('core:editormd/lib', false).'/',
    ]);

    if ($options['height'] == 'auto') {
        $options['autoHeight'] = true;
    }

    return $this->toHtmlString(
        $this->view->make('core::field.markdown')->with(compact('name', 'value', 'options'))->render()
    );
});

/**
 * 编辑器
 */
Form::macro('editor', function($attrs) {

    $attrs['type'] = 'textarea';
    $attrs['rows'] = 18;

    return $this->field($attrs);
});


/**
 * icon 选择器
 */
Form::macro('icon', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getName($attrs);

    $options = $this->getAttribute($attrs, 'options',  [
        'icon'            => $value,
        'cols'            => $this->getAttribute($attrs, 'cols', 10),
        'rows'            => $this->getAttribute($attrs, 'rows', 5),
        'iconset'         => $this->getAttribute($attrs, 'iconset', 'fontawesome5'),
        'selectedClass'   => $this->getAttribute($attrs, 'selectedClass', 'btn-success'),
    ]);

    return $this->toHtmlString(
        $this->view->make('core::field.icon')->with(compact('name', 'value', 'id', 'attrs', 'options'))->render()
    );
});
