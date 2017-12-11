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
}
