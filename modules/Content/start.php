<?php
/**
 * 开始菜单
 */
\Filter::listen('global.start', 'Modules\Content\Hook\Listener@start');

/**
 * 快捷导航
 */
\Filter::listen('global.navbar', 'Modules\Content\Hook\Listener@navbar');

/**
 * 字段类型滤器
 */
\Filter::listen('content::field.types', 'Modules\Content\Hook\Listener@types');

/**
 * 编辑器
 */
\Form::macro('summary', function($attrs) {

    $attrs['type'] = 'textarea';
    $attrs['rows'] = $attrs['rows'] ?? 4;

    return $this->field($attrs);
});
