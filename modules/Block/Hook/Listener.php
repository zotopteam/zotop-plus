<?php
namespace Modules\Block\Hook;

use Route;
use Auth;
use Modules\Block\Models\Block;
use Modules\Block\Models\Datalist;

class Listener
{
    /**
     * 开始菜单扩展
     * 
     * @param  array $start 开始菜单数组
     * @return array
     */
    public function start($start)
    {
        $start['block'] = [
            'text' => trans('block::block.title'),
            'href' => route('block.index'),
            'icon' => 'fa fa-cubes bg-info text-white',
            'tips' => trans('block::block.about'),
        ];
        
        return $start;
    }

    public function navbar($navbar)
    {
        $navbar['block'] = [
            'text'   => trans('block::block.title'),
            'href'   => route('block.index'),
            'active' => Route::is('block.*')

        ];

        return $navbar;
    }

    /**
     * 更新区块设置后，更新区块数据
     * 
     * @param  object $block 区块信息
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
     * @param  object $block 区块信息
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

    /**
     * 卸载钱检查区块是否允许被卸载
     * 
     * @param  view $view 
     * @param  stiring $module 模块名称
     * @return mixed
     */
    public function uninstalling($view, $module)
    {
        // 核心模块不能卸载
        if (strtolower($module) == 'block' && Block::count()) {

            $view->error = trans('block::block.uninstall.forbidden');
            return false;
        }

        return $view;       
    }      
}
