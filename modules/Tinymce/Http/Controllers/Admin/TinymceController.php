<?php

namespace Modules\Tinymce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
//use Modules\Tinymce\Models\Tinymce;

class TinymceController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('tinymce::tinymce.title');

        // 全部获取
        //$this->tinymces = Tinymce::all();
        // 部分获取
        //$this->tinymces = Tinymce::with('some')->where('key','value')->orderby('id','asc')->get();        
        // 分页获取
        //$this->tinymces = Tinymce::with('some')->where('key','value')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('tinymce::tinymce.create');

        $this->tinymce = Tinymce::findOrNew(0);

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
        $tinymce = new Tinymce;
        $tinymce->fill($request->all());
        $tinymce->save();

        return $this->success(trans('core::master.created'), route('tinymce.tinymce.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('tinymce::tinymce.show');

        $this->tinymce = Tinymce::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('tinymce::tinymce.edit');
        $this->id    = $id;
        $this->tinymce = Tinymce::findOrFail($id);

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
        $tinymce = Tinymce::findOrFail($id);
        $tinymce->fill($request->all());        
        $tinymce->save();

        return $this->success(trans('core::master.updated'), route('tinymce.tinymce.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tinymce = Tinymce::findOrFail($id);
        $tinymce->delete();

        return $this->success(trans('core::master.deleted'), route('tinymce.tinymce.index'));        
    }
}
