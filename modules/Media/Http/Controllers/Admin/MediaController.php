<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\Media;
use Modules\Media\Models\Folder;
use Modules\Media\Models\File;
use Modules\Core\Support\FileBrowser;

class MediaController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request, $folder_id=0, $type=null)
    {
        $this->title     = trans('media::media.title');
        $this->folder_id = $folder_id;
        $this->type      = $type ?? $request->input('type');
        $this->keywords  = $request->input('keywords');
        $this->folder    = Folder::find($folder_id);
        $this->parents   = Folder::parents($folder_id, true);
        

        $media = Media::with('user');

        if ($this->keywords) {
            $media->where('name', 'like', '%'.$this->keywords.'%');
        } else {
            $media->where('parent_id', $folder_id);
        }

        $this->media = $media->orderby('sort', 'desc')->paginate(25);

        $this->media->getCollection()->transform(function($item) {
            if ($item->isFolder()) {
                $item->url = route('media.index', $item->id);
            } else {
                $item->url = url($item->url);
            }
            
            return $item;
        });


        return $this->view();
    }

    /**
     * 多选操作
     *
     * @param  Request $request
     * @return Response
     */
    public function operate(Request $request)
    {
        $success = 0;
        $errors  = [];
        $operate = $request->input('operate');
        $ids     = $request->input('ids', []);

        switch ($operate) {
            case 'move':
                $move_folder_id = $request->input('move_folder_id');
                Folder::whereIn('id', $ids)->get()->each(function($item, $key) use($move_folder_id, &$success, &$errors) {
                    $item->parent_id = $move_folder_id;
                    $item->save() ? $success++ : array_push($errors, $item->error);
                });

                File::whereIn('id', $ids)->get()->each(function($item, $key) use($move_folder_id, &$success, &$errors) {
                    $item->parent_id = $move_folder_id;
                    $item->save() ? $success++ : array_push($errors, $item->error);
                });                
                break;
            case 'delete':
                Folder::whereIn('id', $ids)->get()->each(function($item, $key) use(&$success, &$errors) {
                    $item->delete() ? $success++ : array_push($errors, $item->error);
                });

                File::whereIn('id', $ids)->get()->each(function($item, $key) use(&$success, &$errors)  {
                    $item->delete() ? $success++ : array_push($errors, $item->error);
                });
                break;
        }

        // 有错误的，有正确的
        if ($errors && $success) {
            return $this->message([
                'state'   => true,
                'url'     => $request->referer(),
                'time'    => 10,               
                'content' => array_prepend(
                    $errors,
                    '<div class ="text-lg mb-2">'.trans('media::media.operate.result',[$success, count($errors)]).'<div>'
                )
            ]);
        }

        // 全是错误
        if ($errors) {
            return $this->error($errors);
        }

        // 全是正确
        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 从已上传中选择文件
     *
     * @return Response
     */
    public function selectFromUploaded(Request $request)
    {
        $file = File::query();

        $from = $request->only(['module','controller','action','field','data_id','filetype','allow']);

        foreach ($from as $key => $value) {        
            if ($key == 'filetype' && $value) {
                $file->where('type', $value);
            } elseif ($key == 'allow' && $value) {
                $file->whereIn('extension', explode(',', $value));
            } else {
                $file->where($key, $value);
            }
        }
        
        $this->params = $request->all();
        $this->files  = $file->orderby('sort', 'desc')->paginate(24);
        $this->title  = trans('media::media.insert.from.uploaded',[$request->typename]);
        
        return $this->view('media::media.select.uploaded');
    }

    /**
     * 从媒体库中选择文件
     *
     * @return Response
     */
    public function selectFromLibrary(Request $request, $folder_id=0)
    {
        // 参数
        $this->params    = $params = $request->all();

        // 当前文件夹编号
        $this->folder_id = $folder_id;

        // 当前文件夹信息
        $this->folder    = Folder::find($folder_id);

        // 文件夹路径
        $this->parents   = Folder::parents($folder_id, true)->map(function($folder) use($params) {
            $folder->url = route('media.select.library', [$folder->id] + $params);
            return $folder;
        });

        // 根目录
        $this->root_url = route('media.select.library', [0] + $params);

        // 上级url
        if ($this->folder) {
            $this->parent_url = route('media.select.library', [$this->folder->parent_id] + $params);
        } else {
            $this->parent_url = null;
        }

        // 查询数据并分页
        $this->media = Media::where('parent_id', $folder_id)->where(function($query) use ($request) {
            if ($request->filetype) {
                $query->where('type', 'folder')->orWhere('type', $request->filetype);
            }
        })->where(function($query) use ($request) {
            if ($request->allow) {
                $query->whereNull('extension')->orWhereIn('extension', explode(',', $request->allow));
            }
        })->orderby('sort', 'desc')->paginate(48);

        // 补充url字段
        $this->media->getCollection()->transform(function($item) use ($params) {
            if ($item->isFolder()) {
                $item->link = route('media.select.library', [$item->id] + $params);
            } else {
                $item->link = url($item->url);
            }
            return $item;
        });

        $this->title = trans('media::media.insert.from.library',[$request->typename]);
        return $this->view('media::media.select.library');
    }

    /**
     * 从目录中选择文件
     *
     * @return Response
     */
    public function selectFromDir(Request $request, $root='public/uploads')
    {
        $browser = app(FileBrowser::class, [
            'root' => $root,
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

        $this->title = trans('media::media.insert.from.library',[$request->typename]);

        return $this->view('media::media.select.dir');
    }
}
