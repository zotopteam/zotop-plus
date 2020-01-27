<?php

use App\Hook\Facades\Filter;

/**
 * 扩展后台全局导航
 */
Filter::listen('global.start','Modules\Translator\Hooks\Listener@start');

/**
 * 模块管理
 */
Filter::listen('module.manage','Modules\Translator\Hooks\Listener@moduleManage');


if (! function_exists('translate')) {

    /**
     * 翻译
     * 
     * @param  string $text 待翻译文本
     * @param  string $from 源语言
     * @param  string $to   目标语言
     * @return string
     */
    function translate($text, $from=null, $to=null, $engine=null)
    {        
        $translator = new \Modules\Translator\Support\Translator($engine);

        return $translator->translate($text, $from, $to);
    }
}

if (! function_exists('translate_slug')) {

    /**
     * 别名转换
     * 
     * @param  string $text 待转换文本
     * @param  string $separator 分隔符
     * @return string
     */
    function translate_slug($text, $separator='-')
    {  
        $alias = translate($text, 'auto', 'en');
        $alias = str_replace(' ', $separator, $alias);
        $alias = preg_replace("/[[:punct:]]/", $separator, $alias);
        $alias = preg_replace('#['.$separator.$separator.']+#', $separator, $alias);
        $alias = trim($alias, $separator);
        $alias = strtolower($alias);

        return $alias;
    }
}

/**
 * 翻译器
 */
\Form::macro('translate', function($attrs) {
    $value  = $this->getValue($attrs);
    $id     = $this->getId($attrs);
    $name   = $this->getAttribute($attrs, 'name');
    $button = $this->getAttribute($attrs, 'button', trans('translator::translator.button'));
    
    $options = [
        'url'       => $this->getAttribute($attrs, 'url',  route('translator.translate')),
        'source'    => $this->getAttribute($attrs, 'source',  ''),
        'format'    => $this->getAttribute($attrs, 'format', ''),
        'from'      => $this->getAttribute($attrs, 'from', ''),
        'to'        => $this->getAttribute($attrs, 'to', ''),
        'maxlength' => $this->getAttribute($attrs, 'maxlength', '', false),
        'separator' => $this->getAttribute($attrs, 'separator', ''),
    ];

    return $this->toHtmlString(
        $this->view->make('translator::field.translate')->with(compact('name', 'id', 'value', 'attrs', 'button', 'options'))->render()
    );
});


