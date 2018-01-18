<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\Folder;
use Modules\Media\Models\File;

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

        $folder = Folder::query();

        if ($this->keywords) {
            $folder->where('name', 'like', '%'.$this->keywords.'%');
        } else {
            $folder->where('parent_id', $folder_id);
        }

        $this->folders   = $folder->orderby('sort', 'desc')->orderby('created_at', 'asc')->get();         

        $file = File::query();
        
        if ($this->keywords) {
            $file->where('name', 'like', '%'.$this->keywords.'%');
        } else {
            $file->where('folder_id',$folder_id);
        }

        if ($this->type) {
            $file->where('type',$this->type);
        }

        $this->files = $file->orderby('created_at', 'desc')->paginate(25);

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
        $success    = 0;
        $errors     = [];
        $operate    = $request->input('operate');
        $folder_ids = $request->input('folder_ids',[]);
        $file_ids   = $request->input('file_ids',[]);

        switch ($operate) {
            case 'move':
                $move_folder_id = $request->input('move_folder_id');
                Folder::whereIn('id', $folder_ids)->get()->each(function($item, $key) use($move_folder_id, &$success, &$errors) {
                    $item->parent_id = $move_folder_id;
                    $item->save() ? $success++ : array_push($errors, $item->error);
                });

                File::whereIn('id', $file_ids)->get()->each(function($item, $key) use($move_folder_id, &$success, &$errors) {
                    $item->folder_id = $move_folder_id;
                    $item->save() ? $success++ : array_push($errors, $item->error);
                });                
                break;
            case 'delete':
                Folder::whereIn('id', $folder_ids)->get()->each(function($item, $key) use(&$success, &$errors) {
                    $item->delete() ? $success++ : array_push($errors, $item->error);
                });

                File::whereIn('id', $file_ids)->get()->each(function($item, $key) use(&$success, &$errors)  {
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
    public function uploaded(Request $request)
    {
        $file = File::query();

        $from = $request->only(['module','controller','action','field','dataid','filetype','allow']);

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
        $this->files  = $file->orderby('created_at', 'desc')->paginate(24);
        $this->title  = trans('media::media.insert.from.uploaded',[$request->typename]);
        
        return $this->view('media::media.select.uploaded');
    }

    /**
     * 从媒体库中选择文件
     *
     * @return Response
     */
    public function library(Request $request, $folder_id=0)
    {
        $folder = Folder::where('parent_id', $folder_id);
        $file   = File::where('folder_id', $folder_id);

        // 文件类型
        if ($filetype = $request->input('filetype')) {
            $file->where('type', $filetype);
        }

        // 允许的扩展名
        if ($allow = $request->input('allow')) {
            $file->whereIn('extension', explode(',', $allow));
        }    

        $this->params    = $params = $request->all();
        $this->folder_id = $folder_id;
        $this->folder    = Folder::find($folder_id);
        $this->files     = $file->orderby('created_at', 'desc')->paginate(48);
        $this->folders   = $folder->orderby('sort', 'desc')->orderby('created_at', 'desc')->get()->map(function($folder) use($params) {
            $folder->url = route('media.select.library', [$folder->id] + $params);
            return $folder;
        });

        $this->parents   = Folder::parents($folder_id, true)->map(function($folder) use($params) {
            $folder->url = route('media.select.library', [$folder->id] + $params);
            return $folder;
        });

        $this->root_url = route('media.select.library', [0] + $params);

        if ($this->folder) { 
            $this->parent_url = route('media.select.library', [$this->folder->parent_id] + $params);
        }


        $this->title = trans('media::media.insert.from.library',[$request->typename]);
        return $this->view('media::media.select.library');
    }        
}
