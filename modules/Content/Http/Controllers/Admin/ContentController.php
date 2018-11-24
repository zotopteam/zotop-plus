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
        $this->parent = Content::parent($parent_id);

        // 分页获取
        $this->contents = Content::with('user','model')->where('parent_id', $parent_id)->sort()->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create($parent_id, $model_id)
    {
        $this->parent = Content::parent($parent_id);
        $this->model  = Model::find($model_id);
        $this->form   = ModelForm::get($model_id);

        $this->content = Content::findOrNew(0);
        $this->content->parent_id = $parent_id;
        $this->content->model_id  = $model_id;
        $this->content->status    = 'publish';

        $this->content = $this->form->default($this->content);

        $this->title   = trans('content::content.create.model', [$this->model->name]);

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

        return $this->success(trans('core::master.created'), route('content.content.index', $content->parent_id));
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
        $this->id    = $id;
        $this->content = Content::findOrFail($id);

        //dd($this->content->categoryRelation->exists);

        $this->parent = Content::parent($this->content->parent_id);
        $this->model  = Model::find($this->content->model_id);
        $this->form   = ModelForm::get($this->content->model_id);     

        $this->title   = trans('content::content.edit.model', [$this->model->name]);   

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

        return $this->success(trans('core::master.updated'), route('content.content.index', $content->parent_id));
    }

    /**
     * 更改状态
     *
     * @return Response
     */
    public function status($id, $status)
    {
        $content = Content::findOrFail($id);
        $content->status = $status;
        $content->save();

        return $this->success(trans('core::master.operated'), route('content.content.index', $content->parent_id));        
    }

    /**
     * 置顶和取消置顶
     *
     * @return Response
     */
    public function stick($id)
    {
        $content = Content::findOrFail($id);
        $content->stick = $content->stick ? 0 : 1;
        $content->save();

        return $this->success(trans('core::master.operated'), route('content.content.index', $content->parent_id));        
    }      

    /**
     * 排序
     *
     * @return json
     */
    public function sort(Request $request, $parent_id)
    {
        $id    = $request->input('id');
        $sort  = $request->input('sort');
        $stick = $request->input('stick');

        // 将当前列表 $sort 之前的数据的 sort 全部加 1， 为拖动的数据保留出位置
        Content::withoutTimestamps()->where('parent_id', $parent_id)->where('sort','>=', $sort)->increment('sort', 1);        

        // 更新当前数据的排序和置顶信息，如果排在置顶数据之前，自动置顶，如果排在非置顶数据后，自动取消置顶
        Content::where('id', $id)->update([
            'sort'  => $sort,
            'stick' => $stick,
        ]);
        
        return $this->success(trans('core::master.sorted'), $request->referer());
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

        return $this->success(trans('core::master.deleted'), route('content.content.index', $content->parent_id));        
    }
}
