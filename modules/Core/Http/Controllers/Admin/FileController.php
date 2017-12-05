<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Artisan;
use File;
use Plupload;
use Filter;

class FileController extends AdminController
{
    /**
     * 文件编辑器
     * 
     * @param  Request $request
     * @param  string  $name    模型名称
     * @return mixed
     */
    public function editor(Request $request)
    {
        $this->file    = $request->input('file');
        $this->path    = base_path($this->file);

        // 保存数据
        if ($request->isMethod('POST')) {

            File::put($this->path, $request->input('content'));

            return $this->success(trans('core::master.saved'));
        }        
        

        $this->title   = trans('core::file.edit');        
        $this->content = File::get($this->path);
        $this->mode    = File::extension($this->path);

        return $this->view();
    }

    /**
     * 新建文件
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
                return $this->error(trans('core::file.name.required'));
            }

            if (!strpos($newname,'.')) {
                return $this->error(trans('core::file.extension.required'));
            }

            $newpath = $this->path.'/'.$newname;
            $content = $newname;

            if (File::exists($newpath)) {
                return $this->error(trans('core::file.existed', [$newname]));
            }

            if (File::put($newpath, $content)) {
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
        $this->file    = $request->input('file');
        $this->path    = base_path($this->file);

        // 保存数据
        if ($request->isMethod('POST')) {

            $newname = $request->input('newvalue');

            if (empty($newname)) {
                return $this->error(trans('core::file.name.required'));
            }

            if (!strpos($newname,'.')) {
                $newname = $newname.'.'.File::extension($this->path);
            }

            $newpath = dirname($this->path).'/'.$newname;

            if (File::exists($newpath)) {
                return $this->error(trans('core::file.existed', [$newname]));
            }

            if (File::move($this->path, $newpath)) {
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
        $this->file    = $request->input('file');
        $this->path    = base_path($this->file);

        // 保存数据
        if ($request->isMethod('DELETE')) {

            if (File::delete($this->path)) {
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

    /**
     * 文件上传
     * 
     * @return [type] [description]
     */
    public function upload(Request $request, $type)
    {
        // 获得multipart_params传过来的数据
        $params = $request->all();

        return Plupload::receive('file', function ($tempfile) use($type, $params) {

            $basepath = '/uploads/'.$type.'/'.date('Y/m/d',time()).'/';         
            $savepath = public_path($basepath);
            $filename = date('YmdHisu', time()).rand(1000,9999).'.'.File::extension($tempfile->getClientOriginalName());

            // 如果目录不存在，尝试创建目录
            if (! File::exists($savepath)) {
                File::makeDirectory($savepath, 0775, true);
            }

            // 移动文件
            $file = $tempfile->move($savepath, $filename);

            // 返回处理结果
            return Filter::fire('core.file.upload', [
                'status'    => true,
                'name'      => $params['filename'],
                'type'      => $type,
                'mimetype'  => $file->getMimeType(),
                'extension' => $file->getExtension(),
                'size'      => $file->getSize(),
                'path'      => $basepath.$filename,
                'url'       => $basepath.$filename,
            ], $file, $params);
        });
    }     
}
