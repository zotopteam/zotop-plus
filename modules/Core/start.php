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
\Module::macro('getFileData', function($module, $file) {
    
    $data = [];

    $file = $this->getModulePath($module).$file;

    if ($this->app['files']->isFile($file)) {
        $data = require $file;
        $data = is_array($data) ? $data : [];
    }

    return $data;
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
        'icon'  => 'fa fa-magic', 
        'text'  => trans('core::master.refresh'),
        'title' => trans('core::master.refresh.description'),
        'href'  => route('core.system.refresh'),
        'class' => 'refresh js-post', 
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
 * 单图片上传
 *
 * 
 * {field type="upload_image" value=""}
 * {field type="upload_image" value="" button="Upload" url="route('core.plupload.image')" resize="['width'=>1920,height=>800,quality=>100,crop=>false]" params=>"[]"}
 */
\Form::macro('upload_image', function($attrs){
    
    $value = $this->getValue($attrs);
    $name  = $this->getAttribute($attrs, 'name');    

    // 上传URL
    $url = $this->getAttribute($attrs, 'url',  route('core.plupload.image'));

    // 图片缩放
    $resize = $this->getAttribute($attrs, 'resize',  [
        'width'   => config('module.core.upload.resize.width', 1920),
        'height'  => config('module.core.upload.resize.height', 1920),
        'quality' => config('module.core.upload.resize.quality', 100),
        'crop'    => config('module.core.upload.resize.crop',  false)
    ]);

    // 附加参数
    $params = $this->getAttribute($attrs, 'params',  [
        'userid' => Auth::user()->id,
        'token'  => Auth::user()->token
    ]);

    $options = $this->getAttribute($attrs, 'options',  [
        'url'              => $url,
        'resize'           => $resize,
        'multipart_params' => $params
    ]);

    // 按钮文字
    $button   = $this->getAttribute($attrs, 'button', trans('core::field.upload.image.button'));

    // 高级上传及工具
    $tools = $this->getAttribute($attrs, 'tools', \Filter::fire('upload_image.tools',  [
        'select'   => ['text'=>trans('core::field.upload.image.select'),'icon'=>'fa-cloud', 'herf'=>'', 'class'=>'js-open'],
        'libarary' => ['text'=>trans('core::field.upload.image.library'),'icon'=>'fa-database', 'herf'=>'', 'class'=>'js-open'],
        'dir'      => ['text'=>trans('core::field.upload.image.select'),'icon'=>'fa-folder', 'herf'=>'', 'class'=>'js-open'],
    ]));

    return $this->toHtmlString(
        $this->view->make('core::field.upload_image')
            ->with('name', $name)->with('value', $value)->with('attrs', $attrs)->with('options', $options)
            ->with('button', $button)->with('tools', $tools)
            ->render()
    );
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
        $this->view->make('core::field.datetime')
            ->with('name', $name)->with('value', $value)->with('attrs', $attrs)->with('options', $options)
            ->render()
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
        $this->view->make('core::field.radiogroup')->with('name', $name)->with('value', $value)->with('column', $column)->with('options', $options)->with('class', $class)->render()
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
    $options = $this->getAttribute($attrs, 'options',  []);
    $column  = $this->getAttribute($attrs, 'column', 0);
    $class   = $this->getAttribute($attrs, 'class', 'checkboxgroup-default');
    return $this->toHtmlString(
        $this->view->make('core::field.checkboxgroup')->with('name', $name)->with('value', $value)->with('column', $column)->with('options', $options)->with('class', $class)->render()
    );
});
