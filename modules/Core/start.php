<?php
/**
 * 开始菜单
 */
\Filter::listen('global.start', 'Modules\Core\Hook\Listener@start', 99999);

/**
 * 快捷导航
 */
\Filter::listen('global.navbar', 'Modules\Core\Hook\Listener@navbar', 1);

/**
 * 快捷工具
 */
\Filter::listen('global.tools', 'Modules\Core\Hook\Listener@tools');

/**
 * 文件上传
 */
\Filter::listen('core.file.upload', 'Modules\Core\Hook\Listener@upload');

/**
 * 扩展 Request::referer 功能，暂时等于 URL::previous()
 */
\Request::macro('referer', function() {
    return \URL::previous();
});

/**
 * 扩展 Route:active 如果是当前route，则返回 active
 */
\Route::macro('active', function($route, $active="active", $normal='') {
    return Route::is($route) ? $active : $normal;
});

/**
 * 扩展$module->getFileData
 */
\Nwidart\Modules\Module::macro('getFileData', function($file, array $args=[]) {
    $data = [];
    $file = $this->getExtraPath($file);
    if ($this->app['files']->isFile($file)) {
        $data = value(function() use ($file, $args) {
            @extract($args);
            $data = require $file;
            return is_array($data) ? $data : [];
        });
    }
    return $data;
});

/**
 * 扩展Module::getFileData方法, 获取数组文件数据
 */
\Nwidart\Modules\Facades\Module::macro('getFileData', function($module, $file, array $args=[]) {
    return $this->find($module)->getFileData($file, $args);
});

/**
 * 扩展data方法, 从data目录获取数组文件数据
 */
\Nwidart\Modules\Facades\Module::macro('data', function($name, array $args=[]) {
    list($module, $file) = explode('::', $name);
    $data = static::getFileData($module, "Data/{$file}.php", $args);
    return \Filter::fire($name, $data, $args);
});

/**
 * 扩展File::mime方法, 获取文件类型audio/avi，text/xml 斜杠前面部分  
 */
\File::macro('mime', function($file) {
    if ($mimeType = static::mimeType($file)) {
        list($mime, $type) = explode('/', $mimeType);
        return $mime;
    }
    return null;
});

/**
 * 扩展File::humanType方法, 获取文件的类型，依据系统可上传的类型判断
 */
\File::macro('humanType', function($file) {
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
\File::macro('icon', function(...$file) {
    $icon = \Module::data('core::file.icon');
    foreach(func_get_args() as $file) {
        $extension = strpos($file, '.') ? static::extension($file) : trim($file, '.');
        if (isset($icon[$extension])) {
            return $icon[$extension];
        }
    }
    return 'fa-file';
});

/**
 * 扩展上传的获取文件类型，依据系统可上传的类型判断
 */
\Illuminate\Http\UploadedFile::macro('getHumanType',function() {
    $extension = $this->getClientOriginalExtension();
    return \File::humanType($extension);
});

/**
 * 单图片上传
 *
 * 
 * {field type="static" value=""}
 */
\Form::macro('static', function($attrs){
    $value = $this->getValue($attrs);
    return '<p class="form-control-plaintext">'.$value.'</p>';
});

/**
 * 文件上传
 */
\Form::macro('upload', function($attrs) {
    
    // 标签预处理
    $attrs = Filter::fire('core.field.upload.attrs', $attrs);

    // 标签参数分解
    $value    = $this->getValue($attrs);
    $name     = $this->getAttribute($attrs, 'name');
    $id       = $this->getIdAttribute($name, $attrs);
    $filetype = $this->getAttribute($attrs, 'filetype', 'files');
    $url      = $this->getAttribute($attrs, 'url', route('core.file.upload',[$filetype]));
    $allow    = $this->getAttribute($attrs, 'allow', config('core.upload.types.'.$filetype.'.extensions'));
    $maxsize  = $this->getAttribute($attrs, 'maxsize', config('core.upload.types.'.$filetype.'.maxsize'));
    $typename = $this->getAttribute($attrs, 'typename', trans('core::file.type.'.$filetype));
    $select   = $this->getAttribute($attrs, 'select', trans('core::field.upload.select',[$typename])); 
    $icon     = $this->getAttribute($attrs, 'icon', 'fa-upload');
    $button   = $this->getAttribute($attrs, 'button', trans('core::field.upload.button',[$typename]));
    $folder   = $this->getAttribute($attrs, 'folder', '');
    $data_id  = $this->getAttribute($attrs, 'data_id', '');

    // 附加参数
    $params = $this->getAttribute($attrs, 'params',  [
        'select'     => 1,
        'allow'      => $allow,
        'maxsize'    => $maxsize,
        'filetype'   => $filetype,
        'typename'   => $typename,
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => $name,
        'folder'     => $folder,
        'data_id'    => $data_id,
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
            'mime_types'         => [['title'=>$select, 'extensions'=>$allow]],
            'prevent_duplicates' => true,
        ] 
    ]);

    // 高级上传及工具
    $tools = $this->getAttribute($attrs, 'tools', Module::data('core::field.upload.tools', $params));

    return $this->toHtmlString(
        $this->view->make('core::field.upload')
            ->with(compact('id', 'name', 'value', 'attrs', 'options', 'icon', 'button', 'select', 'tools'))
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
\Form::macro('upload_image', function($attrs) {
    
    // 标签预处理
    $attrs = Filter::fire('core.field.upload_image.attrs', $attrs);
    $attrs = $attrs + [
        'filetype' => 'image',
        'typename' => trans('core::file.type.image'),
        'icon'     => 'fa-image',
        'allow'    => $this->getAttribute($attrs, 'allow', config('core.upload.types.image.extensions')),
        'preview'  => $this->getAttribute($attrs, 'preview', 'image')
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
 * 日期选择器
 */
\Form::macro('date', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name');

    $options = $this->getAttribute($attrs, 'options',  [
        'inline'     => $this->getAttribute($attrs, 'inline', false),
        'icon'       => $this->getAttribute($attrs, 'icon', false),
        'format'     => $this->getAttribute($attrs, 'format', 'Y-m-d'),
        'timepicker' => $this->getAttribute($attrs, 'time', false),
        'datepicker' => $this->getAttribute($attrs, 'date', true),
        'lang'       => $this->getAttribute($attrs, 'lang', App::getLocale()),
        'minField'   => $this->getAttribute($attrs, 'min-field', false),
        'maxField'   => $this->getAttribute($attrs, 'max-field', false),
        'minDate'    => $this->getAttribute($attrs, 'min-date', false),
        'maxDate'    => $this->getAttribute($attrs, 'max-date', false),
        'startDate'  => $this->getAttribute($attrs, 'start', false),
    ]);

    // 追加标签
    $attrs =  $attrs + [
        'class'       => 'form-control-date',
        'data-toggle' => 'date'
    ];    

    return $this->toHtmlString(
        $this->view->make('core::field.datetime')->with(compact('name', 'value', 'id', 'attrs', 'options'))->render()
    );
});

/**
 * 日期时间选择器
 */
\Form::macro('datetime', function($attrs) {

    $attrs['format'] = 'Y-m-d H:i';
    $attrs['time']   = true;

    return $this->macroCall('date',  [$attrs]);
});

/**
 * 时间选择器
 */
\Form::macro('time', function($attrs) {

    $attrs['format'] = 'H:i';
    $attrs['time']   = true;
    $attrs['date']   = false;

    return $this->macroCall('date',  [$attrs]);
});


/**
 * 单选组
 */
\Form::macro('radiogroup', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getAttribute($attrs, 'name');    
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
\Form::macro('radiocards', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getAttribute($attrs, 'name');    
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
\Form::macro('toggle', function($attrs) {
    $name = $this->getAttribute($attrs, 'name');
    $value = $this->getAttribute($attrs, 'value', 1);
    
    $checked = $this->getAttribute($attrs, 'checked');
    $checked = in_array(strtolower($checked), ['checked','true']) ? true : null;

    $class = $this->getAttribute($attrs, 'class');
    $class = trim('toggle '.$class);

    return $this->checkbox($name, 1, $checked, compact('class'));
});

/**
 * 是/否
 */
\Form::macro('bool', function($attrs) {
    // options
    $attrs['options'] = $this->getAttribute($attrs, 'options',  [
        1 => trans('core::master.yes'),
        0 => trans('core::master.no')
    ]);
    return $this->macroCall('radiogroup',  [$attrs]);
});

/**
 * 是/否
 */
\Form::macro('enable', function($attrs){
    // options
    $attrs['options'] = $this->getAttribute($attrs, 'options',  [
        1 => trans('core::master.enable'),
        0 => trans('core::master.disable')
    ]);
    return $this->macroCall('radiogroup',  [$attrs]);
});

/**
 * 多选组
 */
\Form::macro('checkboxgroup', function($attrs){
    $value   = $this->getValue($attrs);
    $value   = is_array($value) ? $value : [];
    $name    = $this->getAttribute($attrs, 'name');    
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
\Form::macro('code', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getAttribute($attrs, 'name');

    $options = $this->getAttribute($attrs, 'options',  [
        'width'         => $this->getAttribute($attrs, 'width', '100%'),
        'height'        => $this->getAttribute($attrs, 'height', '600'),
        'mode'          => $this->getAttribute($attrs, 'mode', 'text/html'),
        'watch'         => $this->getAttribute($attrs, 'watch', false),
        'toolbar'       => $this->getAttribute($attrs, 'toolbar', false),
        'codeFold'      => $this->getAttribute($attrs, 'codeFold', true),
        'searchReplace' => $this->getAttribute($attrs, 'searchReplace', true),
        'theme'         => $this->getAttribute($attrs, 'theme','default'),
        'path'          => \Module::asset('core:editormd/lib').'/',
    ]);

    if ($options['height'] == 'auto') {
        $options['autoHeight'] = true;
    }

    return $this->toHtmlString(
        $this->view->make('core::field.code')->with(compact('name', 'value', 'options'))->render()
    );
});
