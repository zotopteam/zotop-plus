<?php
/**
 * 扩展 Request::referer 功能，暂时等于 URL::previous()
 */
\Request::macro('referer', function() {

    $referer = \URL::previous();

    return $referer;
});


/**
 * 扩展 Route:active 如果是当前route，则返回 active
 */
\Route::macro('active', function($route, $active="active", $normal='') {
    return Route::is($route) ? $active : $normal;
});

/**
 * 扩展getFileData方法, 获取数组文件数据
 */
\Module::macro('getFileData', function($module, $file, array $args=[]) {
    $data = [];
    $file = $this->getModulePath($module).$file;
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
 * 扩展data方法, 从data目录获取数组文件数据
 */
\Module::macro('data', function($name, array $args=[]) {
    list($module, $file) = explode('::', $name);
    $data = static::getFileData($module, "Data/{$file}.php", $args);
    return \Filter::fire($name, $data);
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
 * 全局导航
 */
\Filter::listen('global.navbar', function($navbar){
        
    // 主页
    $navbar['core.index'] = [
        'text'   => trans('core::master.index'),
        'href'   => route('admin.index'),
        'class'  => 'index', 
        'active' => Route::is('admin.index')
    ];

    return $navbar;
    
},1);

/**
 * 快捷方式
 */
\Filter::listen('global.start', function($navbar){
    
    //编辑我的资料
    $navbar['mine-edit'] = [
        'text' => trans('core::mine.edit'),
        'href' => route('core.mine.edit'),
        'icon' => 'fa fa-user-circle bg-primary text-white', 
        'tips' => trans('core::mine.edit.description'),
    ];

    //修改我的密码
    $navbar['mine-password'] = [
        'text' => trans('core::mine.password'),
        'href' => route('core.mine.password'),
        'icon' => 'fa fa-key bg-primary text-white', 
        'tips' => trans('core::mine.password.description'),
    ];

    //管理员快捷方式
    $navbar['administrator'] = [
        'text' => trans('core::administrator.title'),
        'href' => route('core.administrator.index'),
        'icon' => 'fa fa-users bg-primary text-white', 
        'tips' => trans('core::administrator.description'),
    ];

    //管理员快捷方式
    $navbar['core-config'] = [
        'text' => trans('core::config.title'),
        'href' => route('core.config.index'),
        'icon' => 'fa fa-cogs bg-primary text-white', 
        'tips' => trans('core::config.description'),
    ];    

    //模块管理
    $navbar['themes'] = [
        'text' => trans('core::themes.title'),
        'href' => route('core.themes.index'),
        'icon' => 'fa fa-universal-access bg-primary text-white', 
        'tips' => trans('core::themes.description'),
    ];
      
    //模块管理
    $navbar['modules'] = [
        'text' => trans('core::modules.title'),
        'href' => route('core.modules.index'),
        'icon' => 'fa fa-puzzle-piece bg-primary text-white', 
        'tips' => trans('core::modules.description'),
    ];

    //environment 服务器环境
    $navbar['environment'] = [
        'text' => trans('core::system.environment.title'),
        'href' => route('core.system.environment'),
        'icon' => 'fa fa-server bg-primary text-white', 
        'tips' => trans('core::system.environment.description'),
    ];

    $navbar['about'] = [
        'text' => trans('core::system.about.title'),
        'href' => route('core.system.about'),
        'icon' => 'fa fa-info-circle bg-primary text-white', 
        'tips' => trans('core::system.about.description'),
    ];        
    
    return $navbar;

},100);

/**
 * 全局工具
 */
\Filter::listen('global.tools', function($tools){
        
    // 一键刷新
    $tools['refresh'] = [
        'icon'     => 'fa fa-magic', 
        'text'     => trans('core::master.refresh'),
        'title'    => trans('core::master.refresh.description'),
        'href'     => 'javascript:;',
        'data-url' => route('core.system.refresh'),
        'class'    => 'refresh js-post',
    ];

    return $tools;
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
    
    $value    = $this->getValue($attrs);
    $name     = $this->getAttribute($attrs, 'name');
    $id       = $this->getIdAttribute($name, $attrs);
    $filetype = $this->getAttribute($attrs, 'filetype', 'files');
    $typename = $this->getAttribute($attrs, 'typename', trans('core::file.type.files'));
    $allow    = $this->getAttribute($attrs, 'allow', config('core.upload.types.'.$filetype.'.extensions'));
    $maxsize  = $this->getAttribute($attrs, 'maxsize', config('core.upload.types.'.$filetype.'.maxsize'));
    $select   = $this->getAttribute($attrs, 'select', trans('core::field.upload.select',[$typename]));
    $url      = $this->getAttribute($attrs, 'url', route('core.file.upload',[$filetype]));
    $icon     = $this->getAttribute($attrs, 'icon', 'fa-file');
    $button   = $this->getAttribute($attrs, 'button', trans('core::field.upload.button',[$typename]));

    // 附加参数
    $params = $this->getAttribute($attrs, 'params',  [
        'allow'      => $allow,
        'maxsize'    => $maxsize,
        'filetype'   => $filetype,
        'typename'   => $typename,
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => $name,
        'userid'     => Auth::user()->id,
        'token'      => Auth::user()->token
    ]);

    // 选项
    $options = $this->getAttribute($attrs, 'options',  [
        'url'              => $url,
        'filters'          => [
            'max_file_size'      => $maxsize.'mb',
            'mime_types'         => [['title'=>$select, 'extensions'=>$allow]],
            'prevent_duplicates' => true,
        ],           
        'multipart_params' => $params,
    ]);

    // 高级上传及工具
    $tools = $this->getAttribute($attrs, 'tools', Module::data('core::field.upload.tools', [
        'filetype' => $filetype,
        'typename' => $typename,
    ]));

    return $this->toHtmlString(
        $this->view->make('core::field.upload')
            ->with(compact('id', 'name', 'value', 'attrs', 'options','icon','button','tools'))
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
    
    $attrs['filetype'] = 'image';
    $attrs['typename'] = trans('core::file.type.image');
    $attrs['icon']     = 'fa-image';
    $attrs['allow']    = $this->getAttribute($attrs, 'allow', config('core.upload.types.image.extensions'));

    // 系统是否开启图片压缩
    $resize = config('core.image.resize.enabled', true) ? [
        'width'   => config('core.image.resize.width', 1920),
        'height'  => config('core.image.resize.height', 1920),
        'quality' => config('core.image.resize.quality', 100),
        'crop'    => config('core.image.resize.crop',  false)
    ] : [];
    
    $attrs['options']  = ['resize' => $this->getAttribute($attrs, 'resize', $resize)];

    return $this->macroCall('upload',  [$attrs]);
});

/**
 * 日期选择器
 */
\Form::macro('date', function($attrs) {

    $value = $this->getValue($attrs);
    $name  = $this->getAttribute($attrs, 'name');    
    
    $options = $this->getAttribute($attrs, 'options',  [
        'inline'     => $this->getAttribute($attrs, 'inline', false),
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

    return $this->toHtmlString(
        $this->view->make('core::field.datetime')->with(compact('name', 'value', 'attrs', 'options'))->render()
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
    $value   = $this->getValue($attrs, []);
    $name    = $this->getAttribute($attrs, 'name');    
    $options = $this->getAttribute($attrs, 'options', []);
    $column  = $this->getAttribute($attrs, 'column', 0);
    $class   = $this->getAttribute($attrs, 'class', 'checkboxgroup-default');
    return $this->toHtmlString(
        $this->view->make('core::field.checkboxgroup')->with(compact('name', 'value', 'column', 'options', 'class'))->render()
    );
});

/**
 * 代码编辑器
 */
\Form::macro('code', function($attrs){
    $value   = $this->getValue($attrs, []);
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
