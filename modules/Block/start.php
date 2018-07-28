<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.start','Modules\Block\Hook\Listener@start', 80);

/**
 * 扩展开始菜单
 */
\Filter::listen('global.navbar', 'Modules\Block\Hook\Listener@navbar');


/**
 * 区块保存后，更新区块data数据
 */
\Action::listen('block.saved', 'Modules\Block\Hook\Listener@blockSaved');

/**
 * 区块删除
 */
\Action::listen('block.deleted', 'Modules\Block\Hook\Listener@blockDeleted');

/**
 * 卸载验证
 */
\Filter::listen('module.uninstalling', 'Modules\Block\Hook\Listener@uninstalling');
