<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\File;

class FileController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('media::file.title');
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

            $file = new File;
            $file->fill($request->all());
            $file->parent_id = $parent_id;
            $file->save();

            return $this->success(trans('core::master.created'), $request->referer());       
        }

        $this->title = trans('media::file.create');
        $this->File = File::findOrNew(0);

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

            $file = File::findOrFail($id);
            $file->fill($request->all());
            $file->save();

            return $this->success(trans('core::master.updated'), $request->referer());       
        }

        $this->title = trans('media::file.edit');
        $this->id    = $id;
        $this->File = File::findOrFail($id);

        return $this->view();
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return $this->success(trans('core::master.deleted'), $request->referer());        
    }

    /**
     * 移动
     *
     * @return Response
     */
    public function move(Request $request, $id)
    {
        $folder_id = $request->input('folder_id');

        $file = File::findOrFail($id);

        if ($file->folder_id == $folder_id) {
            return $this->error(trans('media::file.move.unchange', [$file->name]));
        }

        $file->folder_id = $folder_id;
        $file->save();

        return $this->success(trans('core::master.operated'), $request->referer());        
    }

}
