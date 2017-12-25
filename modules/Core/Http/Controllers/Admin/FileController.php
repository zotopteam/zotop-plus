<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Support\Facades\Format;
use Modules\Core\Support\FileBrowser;
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
     * @param  Request $request
     * @param  string  $type  类型
     * @return array
     */
    public function upload(Request $request, $type=null)
    {
        // 获得multipart_params传过来的数据
        $params = $request->all();

        return Plupload::receive('file', function ($tempFile) use($type, $params) {

            //如果没传入文件类型，则从系统上传设置中匹配，获取不到则禁止上传
            $type = $type ?? $tempFile->getHumanType();

            if (empty($type)) {
                return ['state'=>false, 'content'=>trans('core::file.upload.error.type', [$params['filename']])];
            }

            // 检查文件扩展名是否被允许
            // 开启了plupload.unique_names，防止中文文件名导致的乱码
            // 此处无法得到真实文件名，真实文件名通过 $params['filename']传递
            $extension = $tempFile->getClientOriginalExtension(); 
            $allow     = $params['allow'] ?? config("core.upload.types.{$type}.extensions");

            if (! in_array($extension, explode(',', $allow))) {
                return ['state'=>false, 'content'=>trans('core::file.upload.error.extension', [$params['filename']])];
            }

            // 文件上传信息
            $basepath  = '/uploads/'.$type.'/'.date('Y/m/d',time()).'/';         
            $savepath  = public_path($basepath);
            $filename  = date('YmdHisu', time()).rand(1000,9999).'.'.$extension;

            // 如果目录不存在，尝试创建目录
            if (! File::exists($savepath)) {
                File::makeDirectory($savepath, 0775, true);
            }

            // 移动文件
            $splFile = $tempFile->move($savepath, $filename);

            $return = [
                'state'     => true,
                'content'   => trans('core::file.upload.success', [$params['filename']]),
                'name'      => $params['filename'],
                'type'      => $type,
                'hash'      => md5_file($splFile->getRealPath()),
                'mimetype'  => $splFile->getMimeType(),
                'extension' => $splFile->getExtension(),
                'size'      => $splFile->getSize(),
                'path'      => $basepath.$filename,
                'url'       => $basepath.$filename,
            ];

            // 返回处理结果
            return Filter::fire('core.file.upload', $return, $splFile, $params);
        });
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
