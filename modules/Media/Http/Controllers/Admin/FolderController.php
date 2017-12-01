<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\Folder;

class FolderController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('media::media.title');

        // 全部获取
        //$this->folders = Folder::all();
        // 部分获取
        //$this->folders = Folder::with('some')->where('key','value')->orderby('id','asc')->get();        
        // 分页获取
        //$this->folders = Folder::with('some')->where('key','value')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create(Request $request, $parent_id=0, $from=null)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $folder = new Folder;
            $folder->fill($request->all());

            if ($from=='prompt') {
                $folder->parent_id = $parent_id;
                $folder->name = $request->input('newvalue','New Foleder');
            }    

            $folder->save();

            return $this->success(trans('core::master.created'), $request->referer());       
        }

        $this->title = trans('media::media.create');
        $this->folder = Folder::findOrNew(0);

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
        $folder = new Folder;
        $folder->fill($request->all());
        $folder->save();

        return $this->success(trans('core::master.created'), route('media.folder.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('media::media.show');

        $this->folder = Folder::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('media::media.edit');
        $this->id    = $id;
        $this->folder = Folder::findOrFail($id);

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
        $folder = Folder::findOrFail($id);
        $folder->fill($request->all());        
        $folder->save();

        return $this->success(trans('core::master.updated'), route('media.folder.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return $this->success(trans('core::master.deleted'), route('media.folder.index'));        
    }
}
