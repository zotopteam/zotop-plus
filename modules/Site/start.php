<?php

use App\Hook\Facades\Filter;


/**
 * 全局导航
 */
Filter::listen('global.navbar', 'Modules\Site\Hooks\Hook@navbar');

/**
 * 快捷方式
 */
Filter::listen('global.start', 'Modules\Site\Hooks\Hook@start');


/**
 * 全局工具
 */
Filter::listen('global.tools', 'Modules\Site\Hooks\Hook@tools', 1);

/**
 * 模块管理
 */
Filter::listen('module.manage', 'Modules\Site\Hooks\Hook@moduleManageSite');


/**
 * 模板选择器
 */
\Form::macro('view', function($attrs) {
    $value  = $this->getValue($attrs);
    $name   = $this->getAttribute($attrs, 'name');
    $id     = $this->getIdAttribute($name, $attrs);
    $button = $this->getAttribute($attrs, 'button', trans('site::field.view.select'));
    $select = route('site.view.select', [
        'theme'  => config('site.theme'),
        'module' => $this->getAttribute($attrs, 'module', app('current.module')),
    ]);

    return $this->toHtmlString(
        $this->view->make('site::field.view')->with(compact('id', 'name', 'value', 'button', 'select', 'attrs'))->render()
    );
});

