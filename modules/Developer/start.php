<?php

use Zotop\Hook\Facades\Action;
use Zotop\Hook\Facades\Filter;

/**
 * 扩展后台全局导航
 */
//Filter::listen('global.navbar','Modules\Developer\Hooks\Hook@navbar', 80);

/**
 * 扩展后台开始菜单
 */
Filter::listen('global.start', 'Modules\Developer\Hooks\Hook@start', 10000);

/**
 * 扩展后台工具栏
 */
Filter::listen('global.tools', 'Modules\Developer\Hooks\Hook@tools', 10000);

/**
 * 扩展模块创建
 */
Action::listen('module.make.full', 'Modules\Developer\Hooks\ModuleMake@full');



