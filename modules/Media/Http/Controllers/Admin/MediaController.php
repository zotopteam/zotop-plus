<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Facades\Module;
use Modules\Media\Models\Media;
use Modules\Core\Support\FileBrowser;
use Illuminate\Support\Facades\Storage;
use App\Modules\Routing\AdminController;
use Modules\Core\Support\StorageBrowser;

class MediaController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request, $folder_id = 0)
    {
        $this->title     = trans('media::media.title');
        $this->folder_id = $folder_id;
        $this->media     = Media::find($folder_id);

        // 如果有关键词，则执行全局搜索，否则显示当前文件夹内容
        $this->media_list = Media::with('user')->when($request->keywords, function ($query, $keywords) {
            $query->searchIn('name', $keywords);
        }, function ($query) use ($folder_id) {
            $query->where('parent_id', $folder_id);
        })->sort()->paginate(49);

        return $this->view();
    }

    /**
     * 类型
     *
     * @return Response
     */
    public function type(Request $request, $type)
    {
        $this->title      = trans("core::file.type.{$type}");
        $this->type       = $type;
        $this->media_list = Media::with('user')
            ->searchIn('name', $request->keywords)
            ->where('type', $type)
            ->sort()
            ->paginate(49);

        return $this->view();
    }

    /**
     * 详情
     *
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $this->title = trans("master.show");
        $this->disks = Module::data('core::storage.disks');
        $this->id    = $id;
        $this->media = Media::findOrFail($id);

        return $this->view();
    }

    /**
     * 新建文件夹
     * 
     * @return Response
     */
    public function create(Request $request, $folder_id, $type)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $media = new Media;
            $media->fill($request->all());
            $media->parent_id = $folder_id;
            $media->type = $type;
            $media->is_folder = $type == 'folder' ? 1 : 0;
            $media->save();

            return $this->success(trans('master.created'), $request->referer());
        }
    }

    /**
     * 重命名
     *
     * @return Response
     */
    public function rename(Request $request, $id)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $media = Media::findOrFail($id);
            $media->fill($request->all());
            $media->save();

            return $this->success(trans('master.renamed'), $request->referer());
        }
    }

    /**
     * 移动
     *
     * @return Response
     */
    public function move(Request $request, $folder_id = null)
    {
        if ($request->isMethod('POST')) {

            $id        = $request->input('id');
            $parent_id = $request->input('folder_id');

            // 获取数据并移动
            Media::whereSmart('id', $id)->get()->each(function ($item) use ($parent_id) {
                try {
                    $item->parent_id = $parent_id;
                    $item->save();
                } catch (\App\Traits\Exceptions\NestableMoveException $e) {
                    abort(403, trans('media::media.move.forbidden', [$item->name]));
                }
            });

            return $this->success(trans('master.moved'));
        }

        // 记忆当前选择的文件夹编号，下次进入时，直接显示该文件夹
        $this->folder_id = $request->remember('folder_id', 0);

        // 当前排序的父节点
        $this->media   = Media::find($this->folder_id);

        // 获取当前节点下的全部文件夹或者获取搜索的文件夹
        $this->media_list = Media::when($request->keywords, function ($query, $keywords) {
            $query->searchIn('name', $keywords);
        }, function ($query) {
            $query->where('parent_id', $this->folder_id);
        })->sort()->paginate(49);

        return $this->view();
    }

    /**
     * 下载文件
     *
     * @param Request $request
     * @param integer $id
     * @return Response
     */
    public function download(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $name  = pathinfo($media->name, PATHINFO_FILENAME) . '.' . $media->extension;

        return Storage::disk($media->disk)->download($media->path, $name);
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id = null)
    {
        // 操作项编号可以通过uri或者post传入
        $id = $id ?? $request->input('id');

        Media::whereSmart('id', $id)->get()->each(function ($item) {
            try {
                $item->delete();
            } catch (\App\Traits\Exceptions\NestableDeleteException $e) {
                abort(403, trans('media::media.delete.notempty', [$item->name]));
            }
        });

        return $this->success(trans('master.deleted'), $request->referer());
    }

    /**
     * 从已上传中选择文件
     *
     * @return Response
     */
    public function selectFromUploaded(Request $request)
    {
        $file = Media::where('is_folder', 0);

        // 当传入的有source_id ，从source_id获取，不从 action和field获取
        if ($request->source_id) {
            $from = $request->only(['type', 'extension', 'module', 'controller', 'source_id']);
        } else {
            $from = $request->only(['type', 'extension', 'module', 'controller', 'action', 'field']);
        }

        // 筛选条件
        foreach ($from as $key => $value) {
            $file->whereSmart($key, $value);
        }

        $this->params = $request->all();
        $this->files  = $file->orderby('sort', 'desc')->paginate(49);
        $this->title  = trans('media::media.insert.from.uploaded', [$request->typename]);

        return $this->view('media::media.select.uploaded');
    }

    /**
     * 从媒体库中选择文件
     *
     * @return Response
     */
    public function selectFromLibrary(Request $request, $folder_id = null)
    {
        $this->title = trans('media::media.insert.from.library', [$request->typename]);

        // 记忆当前选择的文件夹编号，下次进入时，直接显示该文件夹
        $this->folder_id = $request->remember('folder_id', 0);

        // 当前文件夹信息
        $this->media    = Media::find($this->folder_id);

        // 查询数据并分页
        $this->media_list = Media::when($request->keywords, function ($query, $keywords) {
            $query->searchIn('name', $keywords);
        }, function ($query) {
            $query->where('parent_id', $this->folder_id);
        })->where(function ($query) use ($request) {
            $query->where('type', 'folder')->orWhere(function ($query) use ($request) {
                $query->whereSmart('type', $request->type)->whereSmart('extension', $request->extension);
            });
        })->sort()->paginate(49);


        return $this->view('media::media.select.library');
    }
}
