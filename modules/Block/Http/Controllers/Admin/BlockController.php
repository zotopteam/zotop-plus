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
        $this->blocks = Block::sorted()->where('category_id', $this->category->id)->get();

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create($category_id, $type)
    {
        $this->title    = trans('block::block.create.type', [Block::type($type, 'name')]);
        $this->type     = $type;
        $this->category = Category::findOrFail($category_id); 
        $this->block    = Block::findOrNew(0);

        // 默认数据
        $this->block->category_id = $category_id;
        $this->block->interval    = 0;
        $this->block->template    = Block::type($type, 'template', 'block::'.$type);

        // 获取创建视图
        $view = Block::type($type, 'create', 'block::block.create');

        return $this->view($view);
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

        return $this->success(trans('core::master.created'), route('block.data',$block->id));
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
        $this->title = trans('block::block.setting');
        $this->id    = $id;
        $this->block = Block::findOrFail($id);

        // 获取创建视图
        $view = Block::type($this->block->type, 'edit', 'block::block.edit');

        return $this->view($view);
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

        return $this->success(trans('core::master.updated'), $request->referer());
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

    /**
     * 内容维护
     * @param  int $id 区块编号
     * @return Response
     */
    public function data(Request $request, $id)
    {
        // 保存数据
        if ($request->isMethod('POST')) {
            
            // 表单验证
            $this->validate($request, [
                'data' => 'required'
            ],[],[
                'data' => trans('block::block.data')
            ]);

            $block = Block::findOrFail($id);
            $block->fill($request->all());        
            $block->save();

            // 保存并返回
            if ($request->input('operation') == 'save-back') {
                return $this->success(trans('core::master.saved'), route('block.index', $block->category_id)); 
            }

            return $this->success(trans('core::master.saved'));            
        }

        $this->title    = trans('block::block.data');
        $this->id       = $id;
        $this->block    = Block::findOrFail($id);
        //$this->category = Category::findOrFail($this->block->category_id);

        // 获取数据维护视图
        $view = Block::type($this->block->type, 'data', 'block::block.data');        

        return $this->view($view);        
    }

    /**
     * 排序
     *
     * @return Response
     */
    public function sort(Request $request)
    {
        $sort = $request->input('sort');

        foreach ($sort as $i => $id) {
            Block::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('core::master.operated'));
    }       
}
