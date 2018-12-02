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
        // 获取父分类
        $this->parent = Content::findOrNew($parent_id);
        $this->parent->id = $this->parent->id ?? 0;
        $this->parent->title = $this->parent->title ?? trans('content::content.root');

        // 获取全部父级
        $this->parents = Content::parents($parent_id, true);

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
        $this->parent = Content::findOrNew($parent_id);
        $this->parent->id = $this->parent->id ?? 0;
        $this->parent->title = $this->parent->title ?? trans('content::content.root');

        // 获取全部父级
        $this->parents = Content::parents($parent_id, true);

        $this->model  = Model::find($model_id);
        $this->form   = ModelForm::get($model_id);

        $this->content = Content::findOrNew(0);
        $this->content->parent_id = $parent_id;
        $this->content->model_id  = $model_id;
        $this->content->status    = 'draft';

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

        $this->parent = Content::findOrNew($this->content->parent_id);
        $this->parent->id = $this->parent->id ?? 0;
        $this->parent->title = $this->parent->title ?? trans('content::content.root');

        // 获取全部父级
        $this->parents = Content::parents($id, false);

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
    public function status(Request $request, $status, $id=null)
    {
        if ($request->isMethod('POST')) {

            // 操作项编号可以通过uri或者post传入
            $id = $id ?? $request->input('id');

            // 单个操作或者批量操作
            $content = is_array($id) ? Content::whereIn('id', $id) : Content::where('id', $id);

            $content->get()->each(function($item, $key) use($request, $status) {
                $item->status = $status;

                if ($status == 'future') {
                    $item->publish_at = $request->input('publish_at');
                }

                $item->save();
            });
    
            return $this->success(trans('core::master.operated'), $request->referer());
        }     
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
        if ($request->isMethod('POST')) {

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

        // 当前排序节点
        $this->sort = Content::findOrFail($request->id);
        
        // 当前排序的父节点
        $this->parent  = Content::findOrNew($parent_id);
        $this->parent->id = $this->parent->id ?? 0;
        $this->parent->title = $this->parent->title ?? trans('content::content.root');

        // 获取全部父节点
        $this->parents = Content::parents($request->id, false);        

        // 获取当前节点下面的全部数据（包含搜索）
        $this->contents = Content::with('user','model')->where('parent_id', $parent_id)->when($request->keywords, function($query, $keywords){
            return $query->where('title', 'like', '%'.$keywords.'%');
        })->sort()->paginate(25);

        return $this->view();        
    }

   /**
     * 移动
     *
     * @return Response
     */
    public function move(Request $request, $parent_id=null)
    {
        if ($request->isMethod('POST')) {

            $id        = $request->input('id');
            $parent_id = $request->input('parent_id');

            // 获取数据并移动
            $content = is_array($id) ? Content::whereIn('id', $id) : Content::where('id', $id);
            $content->get()->each(function($item, $key) use($parent_id) {
                $item->parent_id = $parent_id;
                $item->save();
            });

            return $this->success(trans('core::master.moved'));
        }

        // 缓存当前选择的节点编号，下次进入时候直接展示该节点
        if (!is_null($parent_id)) {
            session(['content_move_parent_id'=>$parent_id]);
        } else {
            $parent_id = session('content_move_parent_id', 0);
        }

        // 当前排序的父节点
        $this->parent   = Content::findOrNew($parent_id);
        $this->parent->id = $this->parent->id ?? 0;
        $this->parent->title = $this->parent->title ?? trans('content::content.root');

        // 获取全部父节点
        $this->parents = Content::parents($parent_id, true);      

        // 获取当前节点下面的全部数据（包含搜索）
        $this->contents = Content::whereHas('model', function($query) {
            $query->where('nestable', 1);
        })->where('parent_id', $parent_id)->when($request->keywords, function($query, $keywords) {
            return $query->where('title', 'like', '%'.$keywords.'%');
        })->sort()->paginate(36);

        return $this->view();
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id=null)
    {
        // 操作项编号可以通过uri或者post传入
        $id = $id ?? $request->input('id');

        // 单个操作或者批量操作
        $content = is_array($id) ? Content::whereIn('id', $id) : Content::where('id', $id);

        $content->get()->each(function($item, $key) {
            $item->delete();
        });        

        return $this->success(trans('core::master.deleted'), $request->referer());        
    }
}
