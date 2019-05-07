<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Media\Models\Media;
use Modules\Core\Support\FileBrowser;

class MediaController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request, $parent_id=0, $type=null)
    {
        $this->title     = trans('media::media.title');
        $this->parent_id = $parent_id;
        $this->type      = $type ?? $request->input('type');
        $this->keywords  = $request->input('keywords');
        $this->parent    = Media::findOrNew($parent_id);
        $this->parents   = Media::parents($parent_id, true);
        

        $media = Media::with('user');

        if ($this->keywords) {
            $media->where('name', 'like', '%'.$this->keywords.'%');
        } else {
            $media->where('parent_id', $parent_id);
        }

        $this->media = $media->sort()->paginate(25);

        $this->media->getCollection()->transform(function($item) {
            if ($item->is_folder) {
                $item->url = route('media.index', $item->id);
            } else {
                $item->url = url($item->url);
            }
            return $item;
        });


        return $this->view();
    }

    /**
     * 新建文件夹
     * 
     * @return Response
     */
    public function create(Request $request, $parent_id, $type)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $media = new Media;
            $media->fill($request->all());
            $media->parent_id = $parent_id;
            $media->type = $type;
            $media->is_folder = 1;
            $media->save();

            return $this->success(trans('core::master.created'), $request->referer());       
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

            return $this->success(trans('core::master.renamed'), $request->referer());       
        }

    }

   /**
     * 移动
     *
     * @return Response
     */
    public function move(Request $request, $parent_id=null)
    {
        if ($request->isMethod('POST')) {

            $id        = $request->input('id');
            $parent_id = $request->input('parent_id');

            // 获取数据并移动
            $media = is_array($id) ? Media::whereIn('id', $id) : Media::where('id', $id);
            $media->sort()->get()->each(function($item, $key) use($parent_id) {
                $item->parent_id = $parent_id;
                $item->save();
            });

            return $this->success(trans('core::master.moved'));
        }

        // 缓存当前选择的节点编号，下次进入时候直接展示该节点
        if (!is_null($parent_id)) {
            session(['media_move_parent_id'=>$parent_id]);
        } else {
            $parent_id = session('media_move_parent_id', 0);
        }

        // 当前排序的父节点
        $this->parent   = Media::findOrNew($parent_id);

        // 获取全部父节点
        $this->parents = Media::parents($parent_id, true);      

        // 获取当前节点下面的全部数据（包含搜索）
        $this->media = Media::where('is_folder', 1)->where('parent_id', $parent_id)->when($request->keywords, function($query, $keywords) {
            return $query->where('name', 'like', '%'.$keywords.'%');
        })->sort()->paginate(36);

        return $this->view();
    }    

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id=null)
    {
        // 操作项编号可以通过uri或者post传入
        $id = $id ?? $request->input('id');

        // 单个操作或者批量操作
        $media = is_array($id) ? Media::whereIn('id', $id) : Media::where('id', $id);

        $media->sort()->get()->each(function($item, $key) {
            $item->delete();
        });

        return $this->success(trans('core::master.deleted'), $request->referer());
    }

    /**
     * 从已上传中选择文件
     *
     * @return Response
     */
    public function selectFromUploaded(Request $request)
    {
        $file = Media::where('type', '<>' ,'folder');

        // 当传入的有data_id ，从data_id获取，不从 action和field获取
        if ($request->data_id) {
            $from = $request->only(['type','extension','module','controller','data_id']);
        } else {
            $from = $request->only(['type','extension','module','controller','action','field']);
        }

        // 筛选条件
        foreach ($from as $key => $value) {
            
            if (empty($value)) {
                continue;
            }

            if ($key == 'extension') {
                $file->whereIn('extension', explode(',', $value));
            } else {
                $file->where($key, $value);
            }
        }
        
        $this->params = $request->all();
        $this->files  = $file->orderby('sort', 'desc')->paginate(24);
        $this->title  = trans('media::media.insert.from.uploaded', [$request->typename]);
        
        return $this->view('media::media.select.uploaded');
    }

    /**
     * 从媒体库中选择文件
     *
     * @return Response
     */
    public function selectFromLibrary(Request $request, $parent_id=0)
    {
        // 参数
        $this->params    = $params = $request->all();

        // 当前文件夹编号
        $this->parent_id = $parent_id;

        // 当前文件夹信息
        $this->parent    = Media::find($parent_id);

        // 文件夹路径
        $this->parents   = Media::parents($parent_id, true)->map(function($item) use($params) {
            $item->url = route('media.select.library', [$item->id] + $params);
            return $item;
        });

        // 根目录
        $this->root_url = route('media.select.library', [0] + $params);

        // 上级url
        if ($this->parent) {
            $this->parent_url = route('media.select.library', [$this->parent->parent_id] + $params);
        } else {
            $this->parent_url = null;
        }

        // 查询数据并分页
        $this->media = Media::where('parent_id', $parent_id)->when($request->type, function($query, $type){
            $query->where('type', 'folder')->orWhere('type', $type);
        })->when($request->extension, function($query, $extension){
            $query->whereNull('extension')->orWhereIn('extension', explode(',', $extension));
        })->sort()->paginate(48);

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
            return $item->type == $request->type;
        });

        // 选择文件个数，默认不限制
        $this->select = $request->input('select', 0);

        $this->title = trans('media::media.insert.from.library',[$request->typename]);

        return $this->view('media::media.select.dir');
    }
}
