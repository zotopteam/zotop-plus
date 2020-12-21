<?php

use App\Support\Facades\Filter;


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

