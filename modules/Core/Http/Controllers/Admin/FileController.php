<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Modules\Routing\AdminController;
use Artisan;
use Facades\Modules\Core\Support\Plupload;
use File;
use Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Support\Facades\Format;
use Modules\Core\Support\FileBrowser;
use Modules\Core\Support\Upload;

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

            return $this->success(trans('master.saved'));
        }        
        

        $this->title   = trans('master.edit');        
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
                return $this->success(trans('master.operated'), $request->referer());
            }
            return $this->error(trans('master.operate.failed'));            
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
                return $this->success(trans('master.operated'), $request->referer());
            }
            return $this->error(trans('master.operate.failed'));            
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
                return $this->success(trans('master.deleted'), $request->referer());
            }
            return $this->error(trans('master.deleted.failed'));            
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
                return $this->success(trans('master.operated'), $request->referer());
            }
            return $this->error(trans('master.operate.failed'));            
        }
    } 

    /**
     * 大文件上传，当前采用plupload，支持大文件分片上传
     * @param  Request $request
     * @return array
     */
    public function uploadChunk(Request $request)
    {
        return Plupload::receive('file', function ($file) {
            return Upload::file($file)->save();
        });

    }

    /**
     * 文件上传
     * @param  Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        \Debugbar::disable();
        
        $file = $request->file('file');

        return Upload::file($file)->save();
    }

    /**
     * 文件选择
     * 
     * @param  Request $request
     * @param  string  $type  类型
     * @return array
     */
    public function select(Request $request)
    {       
        $browser = app(FileBrowser::class, [
            'root' => $request->input('root'),
            'dir'  => $request->input('dir')
        ]);

        $this->params   = $browser->params;
        $this->path     = $browser->path;
        $this->upfolder = $browser->upfolder();
        $this->position = $browser->position();
        $this->folders  = $browser->folders();
        $this->files    = $browser->files()->filter(function($item) use($request) {
            return $item->type == $request->filetype;
        });

        // 选择文件个数，默认不限制
        $this->select = $request->input('select', 0);

        return $this->view();
    }    
}
