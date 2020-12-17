<?php

use App\Support\Facades\Filter;

/**
 * 开始菜单
 */
Filter::listen('global.start', 'Modules\Content\Hook\Listener@start');

/**
 * 快捷导航
 */
Filter::listen('global.navbar', 'Modules\Content\Hook\Listener@navbar');

/**
 * 字段类型滤器
 */
Filter::listen('content::field.types', 'Modules\Content\Hook\Listener@types');

/**
 * 内容管理操作
 */
Filter::listen('content.manage', 'Modules\Content\Hook\Listener@contentManage', 100);

/**
 * 内容显示，点击计数
 */
Filter::listen('content.show', 'Modules\Content\Hook\Content@hit', 100);


/**
 * 别名控件
 */
// Form::macro('content_slug', function ($attrs) {
//
//     $attrs['type'] = 'translate';
//     $attrs['source'] = 'title';
//     $attrs['format'] = 'slug';
//
//     return $this->field($attrs);
// });





