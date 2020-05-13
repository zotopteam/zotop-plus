<?php
/*
 * 编辑器模式
 */
\Filter::listen('tinymce.editor.options', 'Modules\Tinymce\Hooks\Listener@options');
\Filter::listen('tinymce.editor.options', 'Modules\Tinymce\Hooks\Listener@tools');
/**
 * 编辑器
 */
\Form::macro('editor', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name', 'editor', false);
    
    // 支持rows高度模式
    if ($rows  = $this->getAttribute($attrs, 'rows', 0)) {
        $attrs['height'] = $rows * 35;
    }

    // 设置数据编号
    $attrs['source_id'] = $this->getAttribute($attrs, 'source_id', $this->getValue($attrs));

    // 编辑器属性，可以为字符串和数组，默认为full模式
    $options = $this->getAttribute($attrs, 'options', 'full', false);
    $options = \Filter::fire('tinymce.editor.options', $options, $attrs);
    $options = array_merge($options, array_only($attrs, [
        'menubar','toolbar','plugins','width','height','language','theme','skin','resize','placeholder'
    ]));    

    debug($options);

    return $this->toHtmlString(
        $this->view->make('tinymce::field.editor')->with(compact('id', 'name', 'value', 'rows', 'options'))->render()
    );
});
