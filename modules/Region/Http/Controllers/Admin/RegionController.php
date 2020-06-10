<?php

namespace Modules\Region\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\AdminController;
use Modules\Region\Models\Region;

class RegionController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($id = 0)
    {
        $this->title   = trans('region::region.title');
        $this->id      = $id;
        $this->region  = Region::find($id);
        $this->regions = Region::where('parent_id', $id)->orderBy('sort')->get();

        //$children = Region::with('children')->where('parent_id', 0)->enabled()->orderBy('sort')->get()->toArray();
        //debug($children);

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

        return $this->success(trans('master.created'), route('region.index', $request->input('parent_id')));
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

        return $this->success(trans('master.operated'), route('region.index', $request->input('parent_id')));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        if ($region->child->count()) {
            return $this->error(trans('region::region.destroy.forbidden'));
        }

        $region->delete();

        return $this->success(trans('master.operated'), $request->referer());
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

        return $this->success(trans('master.operated'));
    }

    /**
     * 禁用
     *
     * @param $id
     * @return Response
     */
    public function disable(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $region->disabled = 1;
        $region->save();

        return $this->success(trans('master.operated'), $request->referer());
    }

    /**
     * 启用
     *
     * @param $id
     * @return Response
     */
    public function enable(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $region->disabled = 0;
        $region->save();

        return $this->success(trans('master.operated'), $request->referer());
    }
}
