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

        $this->folders   = $folder->orderby('sort', 'desc')->orderby('created_at', 'desc')->get();         

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
        $media = new Media;
        $media->fill($request->all());
        $media->save();

        return $this->success(trans('core::master.created'), $request->referer());
    }
}
