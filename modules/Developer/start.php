<?php
use App\Hook\Facades\Action;
use App\Hook\Facades\Filter;

/**
 * 扩展后台全局导航
 */
//Filter::listen('global.navbar','Modules\Developer\Hooks\Hook@navbar', 80);

/**
 * 扩展模块管理
 */
Filter::listen('global.start', 'Modules\Developer\Hooks\Hook@start', 10000);

Action::listen('module.make.full', 'Modules\Developer\Hooks\ModuleMake@full');



