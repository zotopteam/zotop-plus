<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Artisan;
use File;
use Plupload;

class FolderController extends AdminController
{

    /**
     * 新建文件夹
     * 
     * @param  Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $this->path    = $request->input('path');
        $this->path    = base_path($this->path);

        // 保存数据
        if ($request->isMethod('POST')) {

            $newname = $request->input('newvalue');

            if (empty($newname)) {
                return $this->error(trans('core::folder.name.required'));
            }

            $newpath = $this->path.'/'.$newname;

            if (File::exists($newpath)) {
                return $this->error(trans('core::folder.existed', [$newname]));
            }

            if (File::makeDirectory($newpath)) {
                return $this->success(trans('core::master.operated'), $request->referer());
            }
            return $this->error(trans('core::master.operate.failed'));            
        }        

    }

    /**
     * 文件重命名
     * 
     * @param  Request $request
     * @return mixed
     */
    public function rename(Request $request)
    {
        $this->folder = $request->input('folder');
        $this->path   = base_path($this->folder);

        // 保存数据
        if ($request->isMethod('POST')) {

            $newname = $request->input('newvalue');

            if (empty($newname)) {
                return $this->error(trans('core::folder.name.required'));
            }

            $newpath = dirname($this->path).'/'.$newname;

            if (File::exists($newpath)) {
                return $this->error(trans('core::folder.existed', [$newname]));
            }

            if (File::moveDirectory($this->path, $newpath)) {
                return $this->success(trans('core::master.operated'), $request->referer());
            }
            return $this->error(trans('core::master.operate.failed'));            
        }
    }

    /**
     * 文件删除
     * 
     * @param  Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $this->folder = $request->input('folder');
        $this->path   = base_path($this->folder);

        // 保存数据
        if ($request->isMethod('DELETE')) {

            if (File::files($this->path) || File::directories($this->path)) {
                return $this->error(trans('core::folder.delete.notempty', [basename($this->path)]));
            }

            if (File::deleteDirectory($this->path)) {
                return $this->success(trans('core::master.deleted'), $request->referer());
            }
            return $this->error(trans('core::master.deleted.failed'));            
        }
    }

    /**
     * 文件复制
     * 
     * @param  Request $request
     * @return mixed
     */
    public function copy(Request $request)
    {
        $this->file    = $request->input('file');
        $this->path    = base_path($this->file);

        // 保存数据
        if ($request->isMethod('POST')) {

            $newpath = dirname($this->path).'/copy '.basename($this->path);

            if (File::copy($this->path, $newpath)) {
                return $this->success(trans('core::master.operated'), $request->referer());
            }
            return $this->error(trans('core::master.operate.failed'));            
        }
    }
}
