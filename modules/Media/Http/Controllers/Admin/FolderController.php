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
        $this->title = trans('media::folder.title');
        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create(Request $request, $parent_id=0)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $folder = new Folder;
            $folder->fill($request->all());
            $folder->parent_id = $parent_id;
            $folder->save();

            return $this->success(trans('core::master.created'), $request->referer());       
        }

        $this->title = trans('media::folder.create');
        $this->folder = Folder::findOrNew(0);

        return $this->view();
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {      
        // 保存数据
        if ($request->isMethod('POST')) {

            $folder = Folder::findOrFail($id);
            $folder->fill($request->all());
            $folder->save();

            return $this->success(trans('core::master.updated'), $request->referer());       
        }

        $this->title = trans('media::folder.edit');
        $this->id    = $id;
        $this->folder = Folder::findOrFail($id);

        return $this->view();
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);

        if ($folder->delete()) {
            return $this->success(trans('core::master.deleted'), $request->referer());
        }

        return $this->error($folder->error);  
    }

    /**
     * 文件夹选择对话框
     *
     * @return Response
     */    
    public function select(Request $request, $id=0)
    {
        // 组装tree数据
        $tree = Folder::select('id','parent_id','name as title')->orderBy('sort','asc')->get()->map(function($item, $key){
            $item->key    = $item->id;
            $item->folder = true;
            return $item;
        })->toArray();

        $tree = [
            [
                'folder'    => true,
                'key'       => 0,
                'icon'      => 'fas fa-home text-primary',
                'title'     => trans('media::folder.root'),
                'children'  => array_nest($tree)
            ]
        ];

        $this->title = trans('media::folder.select');
        $this->id    = $id;
        $this->tree  = $tree;

        return $this->view();
    }

    /**
     * 移动
     *
     * @return Response
     */
    public function move(Request $request, $id)
    {
        $folder_id = $request->input('folder_id');

        $folder = Folder::findOrFail($id);

        // 未移动
        if ($folder->parent_id == $folder_id) {
            return $this->error(trans('media::folder.move.unchange', [$folder->name]));
        }

        $folder->parent_id = $folder_id;
        
        if ($folder->save()) {
            return $this->success(trans('core::master.operated'), $request->referer());
        }

        return $this->error($folder->error);                
    }      
}
