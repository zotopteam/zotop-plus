<?php

use Zotop\Hook\Facades\Filter;
use Modules\Translator\Support\Translator;

/**
 * 扩展后台全局导航
 */
Filter::listen('global.start', 'Modules\Translator\Hooks\Listener@start');

/**
 * 模块管理
 */
Filter::listen('module.manage', 'Modules\Translator\Hooks\Listener@moduleManage');


if (!function_exists('translate')) {

    /**
     * 翻译
     *
     * @param string $text 待翻译文本
     * @param string $from 源语言
     * @param string $to 目标语言
     * @return string
     */
    function translate($text, $from = null, $to = null, $engine = null)
    {
        $translator = new Translator($engine);

        return $translator->translate($text, $from, $to);
    }
}

if (!function_exists('translate_slug')) {

    /**
     * 别名转换
     *
     * @param string $text 待转换文本
     * @param string $separator 分隔符
     * @return string
     */
    function translate_slug($text, $separator = '-')
    {
        $alias = translate($text, 'auto', 'en');
        $alias = str_replace(' ', $separator, $alias);
        $alias = preg_replace("/[[:punct:]]/", $separator, $alias);
        $alias = preg_replace('#[' . $separator . $separator . ']+#', $separator, $alias);
        $alias = trim($alias, $separator);
        $alias = strtolower($alias);

        return $alias;
    }
}

