<?php

namespace Modules\Block\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zotop\Modules\Routing\AdminController;
use Modules\Block\Models\Block;
use Modules\Block\Models\Category;
use Modules\Block\Http\Requests\BlockRequest;

class BlockController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request, $category_id = 0)
    {
        $this->title = trans('block::block.title');

        // 如果未传入分类编号，则定位到第一个分类上
        if (empty($category_id)) {
            // 获取区块分类中的第一个
            $this->category = Category::first();
            // 如果没有任何区块分类，获取失败，必须先添加
            if (!$this->category) {
                return redirect()->route('block.category.index');
            }
        } else {
            $this->category = Category::findOrFail($category_id);
        }

        // 获取列表
        $this->blocks = Block::with('user')->searchIn('name,slug', request('keywords'))->where('category_id', $this->category->id)->sort()->get();

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
        $this->category = Category::findOrFail($category_id);
        $this->block    = Block::findOrNew(0);

        // 默认数据
        $this->block->type        = $type;
        $this->block->category_id = $category_id;
        $this->block->rows        = 0;
        $this->block->interval    = 0;
        $this->block->view        = Block::type($type, 'view', 'block::' . $type);
        $this->block->fields      = Block::type($type, 'fields', []);

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
    public function store(BlockRequest $request)
    {
        $block = new Block;
        $block->fill($request->all());
        $block->save();

        return $this->success(trans('master.created'), route('block.data', $block->id));
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
    public function update(BlockRequest $request, $id)
    {
        $block = Block::findOrFail($id);
        $block->fill($request->all());
        $block->save();

        return $this->success(trans('master.updated'), $request->referer());
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

        return $this->success(trans('master.deleted'), request()->referer());
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
            ], [], [
                'data' => trans('block::block.data')
            ]);

            $block = Block::findOrFail($id);
            $block->fill($request->all());
            $block->save();

            // 保存并返回
            if ($request->input('operation') == 'save-back') {
                return $this->success(trans('master.saved'), route('block.index', $block->category_id));
            }

            return $this->success(trans('master.saved'));
        }

        $this->title    = trans('block::block.data.edit');
        $this->id       = $id;
        $this->block    = Block::findOrFail($id);

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

        return $this->success(trans('master.sorted'));
    }

    /**
     * 字段显示和添加
     * 
     * @param  Request $request
     * @param  string  $action  动作
     * @return Response
     */
    public function fields(Request $request, $action = '')
    {
        $fields = $request->input('fields');

        // 添加时字段数组尾部增加一条数据，show=0 可以删除
        if ($action == 'add') {
            $fields[] = ['show' => 0, 'label' => '', 'type' => 'text', 'name' => '', 'required' => 'required'];
        }

        $this->fields = $fields;

        return $this->view();
    }
}
