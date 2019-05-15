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
 * 模块管理
 */
\Filter::listen('module.manage', 'Modules\Core\Hook\Listener@moduleManage', 100);
\Filter::listen('module.manage', 'Modules\Core\Hook\Listener@moduleManageCore', 100);

/**
 * 扩展 Request::referer 功能
 */
\Request::macro('referer', function() {

    // 如果前面有传入，比如表单传入
    if ($referer = request()->input('_referer')) {
        return $referer;
    }
    
    return \URL::previous();
});

/**
 * 扩展 Route:active 如果是当前route，则返回 active
 */
\Route::macro('active', function($route, $active="active", $normal='') {
    return Route::is($route) ? $active : $normal;
});

/**
 * 扩展$module->getFileData, 获取文件返回的数据
 */
\Nwidart\Modules\Module::macro('getFileData', function($file, array $args=[], $default=null) {
    $file = $this->getExtraPath($file);

    if (! $this->app['files']->isFile($file)) {
        return $default;
    }

    return value(function() use ($file, $args) {
        @extract($args);
        return require $file;
    });
});

/**
 * 扩展$module->install(), 安装模块
 */
\Nwidart\Modules\Module::macro('install', function($force=false, $seed=true) {

    $name   = $this->getLowerName();

    if (!$force && $this->json()->get('installed', 0)) {
        abort(403, 'This module has been installed');
    }
    
    $this->register();

    $this->fireEvent('installing');
    
    // 迁移数据库
    \Artisan::call('module:migrate', ['module'=>$name, '--force'=>true]);

    if ($seed) {
        \Artisan::call('module:seed', ['module' => $name, '--force'=>true]);
    }

    // 载入配置
    $config = $this->path.'/config.php';
    if (\File::exists($config) && $configs = require $config) {
        \Modules\Core\Models\Config::set($name, $configs);
    }

    // 发布数据
    \Artisan::call('module:publish', ['module' => $name]);

    // 更新 module.json
    $this->json()->set('active', 1)->set('installed', 1)->save();


    $this->fireEvent('installed');    

    // 重启
    \Artisan::call('reboot');
});

/**
 * 扩展$module->uninstall(), 卸载模块
 */
\Nwidart\Modules\Module::macro('uninstall', function() {

    $name = $this->getLowerName();

    // 核心模块不能卸载
    if (in_array($name, config('modules.cores',['core']))) {
        abort(403, 'This is a core module that does not allow operation!');
    }

    $this->fireEvent('uninstalling');

    // 卸载模块数据表
    \Artisan::call('module:migrate-reset', ['module'=>$name]);

    // 删除模块配置
    \Modules\Core\Models\Config::forget($name);

    // 删除已经发布的资源文件
    \File::deleteDirectory(\Module::assetPath($name));

    // 删除模块缓存
    \File::delete(app()->bootstrapPath("cache/{$this->getSnakeName()}_module.php"));

    // 更新 module.json
    $this->json()->set('active', 0)->set('installed', 0)->save();

    $this->fireEvent('uninstalled');

    // 重启
    \Artisan::call('reboot');    
});

/**
 * 扩展$module->update(), 升级模块
 */
\Nwidart\Modules\Module::macro('update', function() {
    
});

/**
 * 扩展Module::getFileData方法, 获取文件返回数据
 */
\Nwidart\Modules\Facades\Module::macro('getFileData', function($module, $file, array $args=[], $default=null) {
    return $this->find($module)->getFileData($file, $args, $default);
});

/**
 * 扩展data方法, 从data目录获取文件返回的数据
 * 
 * @param   $name 模块::文件名称
 * @param   $args 额外参数
 * @example Module::data('tinymce::tools.default', $attrs)
 * @return array
 */
\Nwidart\Modules\Facades\Module::macro('data', function($name, array $args=[], $default=null) {
    list($module, $file) = explode('::', $name);
    $data = static::getFileData($module, "Data/{$file}.php", $args, $default);
    return \Filter::fire($name, $data, $args, $default);
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
    return 'fa fa-file';
});

/**
 * 扩展File::meta, 从文件头部的注释中获取文件文本文件的meta说明信息
 */
\File::macro('meta', function($file, array $headers=[]) {
    
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

    //获取上传配置
    $types = collect(config('core.upload.types'));

    // 标签参数分解
    $value    = $this->getValue($attrs);
    $name     = $this->getAttribute($attrs, 'name');
    $id       = $this->getIdAttribute($name, $attrs);

    // 上传和选择参数
    $filetype  = $this->getAttribute($attrs, 'filetype');

    $url       = $this->getAttribute($attrs, 'url', route('core.file.upload'));
    $allow     = $this->getAttribute($attrs, 'allow', $types->implode('extensions',','));
    $maxsize   = $this->getAttribute($attrs, 'maxsize', 1024);
    $typename  = $this->getAttribute($attrs, 'typename', trans('core::file.type.files'));
    $folder    = $this->getAttribute($attrs, 'folder', '');
    $source_id = $this->getAttribute($attrs, 'source_id', $this->getValueAttribute('source_id'));

    debug($source_id);
    
    // 界面文字和图标
    $select   = $this->getAttribute($attrs, 'select', trans('core::field.upload.select', [$typename])); 
    $icon     = $this->getAttribute($attrs, 'icon', 'fa-upload');
    $button   = $this->getAttribute($attrs, 'button', trans('core::field.upload.button', [$typename]));

    // 附加参数
    $params = $this->getAttribute($attrs, 'params',  [
        'select'     => 1,
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
    $value   = $this->getValue($attrs);
    $id      = $this->getId($attrs);
    $name    = $this->getAttribute($attrs, 'name');

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
\Form::macro('bool', function($attrs) {
    // options
    $attrs['options'] = $this->getAttribute($attrs, 'options',  [
        1 => trans('core::master.yes'),
        0 => trans('core::master.no')
    ]);
    return $this->macroCall('radiogroup',  [$attrs]);
});

/**
 * 启用/禁用
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
        'path'          => \Module::asset('core:editormd/lib').'/',
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
\Form::macro('markdown', function($attrs) {
    $value   = $this->getValue($attrs);
    $name    = $this->getAttribute($attrs, 'name');

    $options = $this->getAttribute($attrs, 'options',  [
        'width'              => $this->getAttribute($attrs, 'width', '100%'),
        'height'             => $this->getAttribute($attrs, 'height', '500'),
        'placeholder'        => $this->getAttribute($attrs, 'placeholder', 'content……'),
        'toolbar'            => $this->getAttribute($attrs, 'toolbar', true),
        'codeFold'           => $this->getAttribute($attrs, 'codeFold', true),
        'saveHTMLToTextarea' => $this->getAttribute($attrs, 'saveHTMLToTextarea', true),
        'htmlDecode'         => $this->getAttribute($attrs, 'htmlDecode', 'style,script,iframe|on*'),
        'theme'              => $this->getAttribute($attrs, 'theme','default'),
        'path'               => \Module::asset('core:editormd/lib').'/',
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
\Form::macro('editor', function($attrs) {

    $attrs['type'] = 'textarea';
    $attrs['rows'] = 18;

    return $this->field($attrs);
});


/**
 * icon 选择器
 */
\Form::macro('icon', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name');

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
