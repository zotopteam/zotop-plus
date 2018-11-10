<?php
/**
 * 开始菜单
 */
\Filter::listen('global.start', 'Modules\Content\Hook\Listener@start');

/**
 * 快捷导航
 */
\Filter::listen('global.navbar', 'Modules\Content\Hook\Listener@navbar');
