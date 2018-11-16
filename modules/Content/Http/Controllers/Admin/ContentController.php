<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Content\Models\Content;
use Modules\Content\Models\Model;
use Modules\Content\Models\Field;
use Modules\Content\Support\ModelForm;

class ContentController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($parent_id=0)
    {
        $this->parent = Content::findOrNew($parent_id);

        if (! $this->parent->exists) {
            $this->parent->id    = $parent_id;
            $this->parent->title = trans('content::content.root');          
        }

        // 分页获取
        $this->contents = Content::with('user')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create($parent_id, $model_id)
    {
        $this->title   = trans('content::content.create');

        $this->content = Content::findOrNew(0);
        $this->content->title = 'ddddd';
        $this->content->content = '<b>aaaaaaaaa</b>';

        $this->fields  = ModelForm::get($model_id);

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
        $content = new Content;
        $content->fill($request->all());
        $content->save();

        return $this->success(trans('core::master.created'), route('content.content.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('content::content.show');

        $this->content = Content::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('content::content.edit');
        $this->id    = $id;
        $this->content = Content::findOrFail($id);

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
        $content = Content::findOrFail($id);
        $content->fill($request->all());        
        $content->save();

        return $this->success(trans('core::master.updated'), route('content.content.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $content->delete();

        return $this->success(trans('core::master.deleted'), route('content.content.index'));        
    }
}
