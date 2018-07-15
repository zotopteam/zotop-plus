<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.start','Modules\Block\Hook\Listener@start', 80);

/**
 * 扩展开始菜单
 */
\Filter::listen('global.navbar', 'Modules\Block\Hook\Listener@navbar');
