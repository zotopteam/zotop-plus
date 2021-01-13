<?php

namespace Modules\Block\Hook;

use Modules\Block\Models\Datalist;
use Route;

class Listener
{
    /**
     * 开始菜单扩展
     *
     * @param array $start 开始菜单数组
     * @return array
     */
    public function start($start)
    {
        if (allow('block.index')) {
            $start['block'] = [
                'text' => trans('block::block.title'),
                'href' => route('block.index'),
                'icon' => 'fa fa-cubes bg-info text-white',
                'tips' => trans('block::block.about'),
            ];
        }

        return $start;
    }

    /**
     * 后台导航扩展
     *
     * @param $navbar
     * @return mixed
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function navbar($navbar)
    {
        if (allow('block.index')) {
            $navbar['block'] = [
                'text'   => trans('block::block.title'),
                'href'   => route('block.index'),
                'active' => Route::is('block.*'),

            ];
        }

        return $navbar;
    }

    /**
     * 更新区块设置后，更新区块数据
     *
     * @param object $block 区块信息
     * @return object
     */
    public function blockSaved($block)
    {
        // 改变区块条数和区块的字段设置时，需要触发数据更新
        if ($block->type == 'list') {
            Datalist::updateBlockData($block->id);
        }

        return $block;
    }

    /**
     * 更新区块设置后，更新区块数据
     *
     * @param object $block 区块信息
     * @return object
     */
    public function blockDeleted($block)
    {
        // 删除区块时，关联删除区块的列表数据，为兼容更多数据库，不使用外键关联
        if ($block->type == 'list') {
            Datalist::where('block_id', $block->id)->delete();
        }

        return $block;
    }
}
