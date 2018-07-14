<?php

namespace Modules\Section\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
//use Modules\Section\Models\Section;

class SectionController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('section::section.title');

        // 全部获取
        //$this->sections = Section::all();
        // 部分获取
        //$this->sections = Section::with('some')->where('key','value')->orderby('id','asc')->get();        
        // 分页获取
        //$this->sections = Section::with('some')->where('key','value')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('section::section.create');

        $this->section = Section::findOrNew(0);

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
        $section = new Section;
        $section->fill($request->all());
        $section->save();

        return $this->success(trans('core::master.created'), route('section.section.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('section::section.show');

        $this->section = Section::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('section::section.edit');
        $this->id    = $id;
        $this->section = Section::findOrFail($id);

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
        $section = Section::findOrFail($id);
        $section->fill($request->all());        
        $section->save();

        return $this->success(trans('core::master.updated'), route('section.section.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return $this->success(trans('core::master.deleted'), route('section.section.index'));        
    }
}
