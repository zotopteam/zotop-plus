<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Support\StorageBrowser;

class StorageController extends Controller
{
    /**
     * 列表
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     * @return Response
     */
    public function index(Request $request, $disk)
    {
        $this->title = $disk;
        $this->disk  = $disk;

        $this->browser = app(StorageBrowser::class, [
            'disk' => $disk,
            'root' => '',
            'dir'  => $request->input('dir')
        ]);

        return $this->view();
    }

    /**
     * 新建文件夹
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function folderCreate(Request $request, $disk)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $path    = $request->input('path');
            $name    = $request->input('name');

            // 是否为空
            if (empty($name)) {
                return $this->error(trans('core::folder.name.required'));
            }

            // 新的路径
            $path = $path.'/'.$name;

            // 是否已经存在
            if (Storage::disk($disk)->exists($path)) {
                return $this->error(trans('core::folder.existed', [$name]));
            }

            // 创建路径
            if (Storage::disk($disk)->makeDirectory($path)) {
                return $this->success(trans('master.operated'), $request->referer());
            }

            return $this->error(trans('master.operate.failed'));            
        }
    }

    /**
     * 重命名文件夹
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function folderRename(Request $request, $disk)
    {
        if ($request->isMethod('POST')) {
            
            $path    = $request->input('path');
            $name    = $request->input('name');

            // 是否为空
            if (empty($name)) {
                return $this->error(trans('core::folder.name.required'));
            }

            // 新路径
            $newpath = File::dirname($path).'/'.$name;

            // 是否存在
            if (Storage::disk($disk)->exists($newpath)) {
                return $this->error(trans('core::folder.existed', [$name]));
            }

            // 移动
            if (Storage::disk($disk)->move($path, $newpath)) {
                return $this->success(trans('master.operated'), $request->referer());
            }

            return $this->error(trans('master.operate.failed'));            
        }
    }

    /**
     * 删除文件夹
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function folderDelete(Request $request, $disk)
    {
        if ($request->isMethod('DELETE')) {

            $path    = $request->input('path');

            // 为确保安全，禁止删除含有文件或者文件夹的目录
            if (Storage::disk($disk)->files($path) || Storage::disk($disk)->directories($path)) {
                return $this->error(trans('core::folder.delete.notempty', [basename($path)]));
            }            

            // 删除目录
            if (Storage::disk($disk)->deleteDirectory($path)) {
                return $this->success(trans('master.operated'), $request->referer());
            }

            return $this->error(trans('master.operate.failed'));            
        }
    }

    /**
     * 重命名文件
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function fileRename(Request $request, $disk)
    {
        if ($request->isMethod('POST')) {

            $path    = $request->input('path');
            $name    = $request->input('name');

            // 名称不能为空
            if (empty($name)) {
                return $this->error(trans('core::file.name.required'));
            }

            //补齐文件后缀
            if (empty(File::extension($name))){
                $name = $name.'.'.File::extension($path);
            }

            // 新的路径
            $newpath = dirname($path).'/'.$name;

            // 新路径是否存在
            if (Storage::disk($disk)->exists($newpath)) {
                return $this->error(trans('core::file.existed', [$name]));
            }

            // 移动
            if (Storage::disk($disk)->move($path, $newpath)) {
                return $this->success(trans('master.operated'), $request->referer());
            }

            return $this->error(trans('master.operate.failed'));            
        }
    }

    /**
     * 删除文件
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function fileDelete(Request $request, $disk)
    {
        if ($request->isMethod('DELETE')) {

            $path    = $request->input('path');        

            // 删除目录
            if (Storage::disk($disk)->delete($path)) {
                return $this->success(trans('master.operated'), $request->referer());
            }

            return $this->error(trans('master.operate.failed'));            
        }
    }

    /**
     * 下载文件
     * 
     * @param  Request $request
     * @param  string $disk 磁盘名称
     * @return mixed
     */
    public function fileDownload(Request $request, $disk)
    {
        $path = $request->input('path');
        $name = basename($path);

        // if file name not in ASCII format
        // if (! preg_match('/^[\x20-\x7e]*$/', basename($path))) {
        //     $name = Str::ascii($name);
        // }

        return Storage::disk($disk)->download($path, $name);
    }                    
}
