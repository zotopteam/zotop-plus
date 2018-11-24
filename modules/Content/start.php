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
 * 标题控件
 */
\Form::macro('content_title', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name');

    // 选项
    $options = $this->getAttribute($attrs, 'options',  []);

    return $this->toHtmlString(
        $this->view->make('content::field.types.content_title')->with(compact('name', 'value', 'id', 'attrs', 'options'))->render()
    );
});

/**
 * 别名控件
 */
\Form::macro('content_slug', function($attrs) {

    $attrs['type']   = 'translate';
    $attrs['source'] = 'title';
    $attrs['format'] = 'slug';

    return $this->field($attrs);
});

/**
 * 关键词控件
 */
\Form::macro('content_keywords', function($attrs) {

    $attrs['type'] = 'text';

    return $this->field($attrs);
});

/**
 * 摘要控件
 */
\Form::macro('content_summary', function($attrs) {

    $attrs['type'] = 'textarea';
    $attrs['rows'] = $attrs['rows'] ?? 4;

    return $this->field($attrs);
});

/**
 * 内容模型选择器
 */
\Form::macro('content_models', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name');

    $models = \Modules\Content\Models\Model::where('disabled', '0')->orderBy('sort', 'asc')->get();

    $models = $models->transform(function($item) use ($name, $value) {

        $item->template = array_get($value, $item->id.'.template', $item->template);
        $item->enabled = array_get($value, $item->id.'.enabled', $value ? 0 : 1);

        return $item;
    })->keyBy('id');
    
    debug($models->toArray());

    return $this->toHtmlString(
        $this->view->make('content::field.types.content_models')->with(compact('name', 'value', 'id', 'attrs', 'models'))->render()
    );
});
