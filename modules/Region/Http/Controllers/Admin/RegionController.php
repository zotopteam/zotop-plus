<?php

namespace Modules\Region\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Region\Models\Region;

class RegionController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($parent_id = 0)
    {
        
        $this->title     = trans('region::region.title');
        $this->parent_id = $parent_id;
        $this->parents   = Region::parents($parent_id, true);
        $this->regions   = Region::where('parent_id', $parent_id)->orderBy('sort')->get();

        //$data = Region::enabled()->nestArray();
        //$data = Region::enabled()->nestJson();
        //$data = Region::parentIds($parent_id, true);
        //$data = Region::parents($parent_id, true);
        //$data = Region::childIds($parent_id);
        //$data = Region::top($parent_id);
        //$data = Region::enabled()->children($parent_id, true)->toArray();
        //$data = Region::enabled()->nestArray(1);
        // $data = Region::parent($parent_id);

        // $data = Region::child($parent_id);
        // debug($data);
        
        return $this->view();
    }

    /**
     * 新建
     *
     * @return Response
     */
    public function create($parent_id = 0)
    {
        $this->region              = Region::findOrNew(0);
        $this->region->parent_id   = $parent_id;
        $parent_region             = Region::find($parent_id);
        $this->parent_region_title = $parent_region ? $parent_region['title'] : trans('region::region.root');

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
        $this->validate($request, ['title' => 'required', 'parent_id' => 'required|numeric']);

        $region = new Region;
        $region->fill($request->all());
        $region->sort = Region::where('parent_id', $request->input('parent_id'))->max('sort') + 1;
        $region->save();

        return $this->success(trans('core::master.created'), route('region.index', $request->input('parent_id')));
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->region = Region::findOrNew($id);

        $parent_region             = Region::find($this->region->parent_id);
        $this->parent_region_title = $parent_region ? $parent_region['title'] : trans('region::region.root');
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
        $this->validate($request, ['title' => 'required', 'parent_id' => 'required|numeric']);

        $region = Region::findOrFail($id);

        $region->fill($request->all());
        $region->save();

        return $this->success(trans('core::master.operated'), route('region.index', $request->input('parent_id')));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if (Region::child($id)->count()) {
            return $this->error(trans('region::module.destroy.forbidden'));
        }

        $region = Region::findOrFail($id);
        $region->delete();

        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 排序
     *
     * @return Response
     */
    public function sort(Request $request)
    {
        $sort_id = $request->input('sort_id');
        foreach ($sort_id as $i => $id) {
            Region::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('core::master.operated'));
    }

    /**
     * 禁用
     *
     * @param $id
     * @return Response
     */
    public function disable(Request $request, $id)
    {
        // 禁用会同时禁用全部下级子节点
        if ($childids = Region::childIds($id, true)) {
            Region::whereIn('id', $childids)->update(['disabled' => 1]);
        }
        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 启用
     *
     * @param $id
     * @return Response
     */
    public function enable(Request $request, $id)
    {
        // 如果父节点被禁用，则无法启用子节点
        if ($parent = Region::parent($id)) {
            return $this->error(trans('region::module.enable.forbidden'));
        }

        // 启用会同时禁用全部下级子节点
        if ($childids = Region::childIds($id, true)) {
            Region::whereIn('id', $childids)->update(['disabled' => 0]);
        }
        return $this->success(trans('core::master.operated'), $request->referer());
    }
}
