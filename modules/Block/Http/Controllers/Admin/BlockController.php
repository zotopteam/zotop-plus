<?php

namespace Modules\Block\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Block\Models\Block;
use Modules\Block\Models\Category;

class BlockController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($category_id=0)
    {
        $this->title = trans('block::block.title');
        
        // 如果未传入分类编号，则定位到第一个分类上
        if (empty($category_id)) {
            // 获取区块分类中的第一个
            $this->category = Category::sorted()->first();
            // 如果没有任何区块分类，获取失败，必须先添加
            if (! $this->category) {
                return redirect()->route('block.category.index');
            }
        } else {
            $this->category = Category::findOrFail($category_id);          
        }

        // 分页获取
        $this->blocks = Block::where('category_id', $this->category->id)->orderby('sort','asc')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('block::block.create');

        $this->block = Block::findOrNew(0);

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $block = new Block;
        $block->fill($request->all());
        $block->save();

        return $this->success(trans('core::master.created'), route('block.block.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('block::block.show');

        $this->block = Block::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('block::block.edit');
        $this->id    = $id;
        $this->block = Block::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $block = Block::findOrFail($id);
        $block->fill($request->all());        
        $block->save();

        return $this->success(trans('core::master.updated'), route('block.block.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $block = Block::findOrFail($id);
        $block->delete();

        return $this->success(trans('core::master.deleted'), route('block.block.index'));        
    }
}
