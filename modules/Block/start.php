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


if (! function_exists('block_tag')) {

    // 解析 {block code="……"} 标签
    function block_tag($attrs)
    {
        $slug  = array_pull($attrs, 'slug');

        $block = \Modules\Block\Models\Block::where('slug', $slug)->first();

        // 如果block存在，解析并返回
        if ($block) {
            $data     = $block->toArray();
            $template = array_pull($data, 'template');
            $view     = app('view');

            if ($view->exists($template)) {
                return $view->make($template)->with($data)->render();
            }

            return '<span class="text-error">'.trans('block::block.view.notexist', [$template, $slug]).'</span>';
        }
        
        // 自动创建block
        // coding
        
        return '<span class="text-error">'.trans('block::block.code.notexist', [$slug]).'</span>';
    }
}
