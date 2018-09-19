<?php
/*
 * 模型自动包含文件
 */
\Filter::listen('tinymce.editor.options', 'Modules\Tinymce\Hook\Listener@options');

/**
 * 编辑器
 */
\Form::macro('editor', function($attrs) {

    $value = $this->getValue($attrs);
    $id    = $this->getId($attrs);
    $name  = $this->getAttribute($attrs, 'name');

    $options = $this->getAttribute($attrs, 'options',  [
        'mode'     => $this->getAttribute($attrs, 'mode', 'full'),
        'menubar'  => $this->getAttribute($attrs, 'menubar', null),
        'toolbar'  => $this->getAttribute($attrs, 'toolbar', null),
        'plugins'  => $this->getAttribute($attrs, 'plugins', null),
        'inline'   => $this->getAttribute($attrs, 'inline', false),
        'width'    => $this->getAttribute($attrs, 'width', '100%'),
        'height'   => $this->getAttribute($attrs, 'height', '300'),
        'language' => $this->getAttribute($attrs, 'language', App::getLocale()),
        'theme'    => $this->getAttribute($attrs, 'theme', 'modern'),
        'skin'     => $this->getAttribute($attrs, 'skin', 'zotop'),
        'resize'   => $this->getAttribute($attrs, 'resize', true),
    ]);

    $options = \Filter::fire('tinymce.editor.options', $options);

    return $this->toHtmlString(
        $this->view->make('tinymce::field.editor')->with(compact('id', 'name', 'value', 'options'))->render()
    );
});
