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

if (! function_exists('block_tag')) {

    // 解析 {block code="……"} 标签
    function block_tag($attrs)
    {
        $code  = array_pull($attrs, 'code');

        $block = \Modules\Block\Models\Block::where('code', $code)->first();

        // 如果block存在，解析并返回
        if ($block) {
            $data     = $block->toArray();
            $template = array_pull($data, 'template');
            $view     = app('view');

            return $view->make($template)->with($data)->render();
        }
        
        // 自动创建block
        
        return $code;
    }
}
