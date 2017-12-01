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
    public function index($folder_id=0)
    {
        $this->title     = trans('media::media.title');
        $this->folder_id = $folder_id;
        $this->folder    = Folder::find($folder_id);
        $this->parents   = Folder::parents($folder_id, true);
        $this->folders   = Folder::where('parent_id',$folder_id)->get();
        $this->files     = File::where('folder_id',$folder_id)->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('media::media.create');

        $this->media = Media::findOrNew(0);

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $media = new Media;
        $media->fill($request->all());
        $media->save();

        return $this->success(trans('core::master.created'), route('media.media.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('media::media.show');

        $this->media = Media::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('media::media.edit');
        $this->id    = $id;
        $this->media = Media::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $media->fill($request->all());        
        $media->save();

        return $this->success(trans('core::master.updated'), route('media.media.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return $this->success(trans('core::master.deleted'), route('media.media.index'));        
    }
}
