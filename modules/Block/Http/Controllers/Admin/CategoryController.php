<?php

namespace Modules\Block\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Block\Models\Category;

class CategoryController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('block::category.title');
    
        // 分页获取
        $this->categories = Category::all();

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

        $this->category = Category::findOrNew(0);

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
        $category = new Category;
        $category->fill($request->all());
        $category->save();

        return $this->success(trans('core::master.created'));
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

        $this->category = Category::findOrFail($id);

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
        $this->category = Category::findOrFail($id);

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
        $category = Category::findOrFail($id);
        $category->fill($request->all());        
        $category->save();

        return $this->success(trans('core::master.updated'), route('block.category.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return $this->success(trans('core::master.deleted'), route('block.category.index'));        
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
            Category::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('core::master.operated'), $request->referer());
    }    
}
