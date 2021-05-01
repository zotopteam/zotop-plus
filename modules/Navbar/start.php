<?php
// Navbar 全局预加载
use Zotop\Support\Facades\Filter;

/**
 * 扩展后台全局导航
 */
Filter::listen('global.start', 'Modules\Navbar\Hooks\Listener@start');

/**
 * 扩展开始菜单
 */
Filter::listen('global.navbar', 'Modules\Navbar\Hooks\Listener@navbar');
