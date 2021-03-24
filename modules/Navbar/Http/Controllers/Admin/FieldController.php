<?php

namespace Modules\Navbar\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use App\Support\Enums\BoolEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Navbar\Http\Requests\Admin\FieldRequest;
use Modules\Navbar\Models\Field;
use Modules\Navbar\Models\Item;
use Modules\Navbar\Models\Navbar;
use Modules\Navbar\Models\QueryFilters\FieldFilter;

class FieldController extends Controller
{
    /**
     * 首页
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Navbar\Models\QueryFilters\FieldFilter $filter
     * @param int $navbarId
     * @param int $parentId
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, FieldFilter $filter, int $navbarId = 0, int $parentId = 0)
    {
        $this->title = trans('navbar::field.title');
        $this->navbar_id = $navbarId;
        $this->parent_id = $parentId;

        $this->navbar = Navbar::find($navbarId);
        $this->parents = $parentId ? Item::find($parentId)->parents : [];

        $this->fields = Field::filter($filter)
            ->where('navbar_id', $navbarId)
            ->where('parent_id', $parentId)
            ->get();

        return $this->view();
    }

    /**
     * 排序
     *
     * @param \Illuminate\Http\Request $request
     * @param int $navbarId
     * @param int $parentId
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-31
     */
    public function sort(Request $request, int $navbarId = 0, int $parentId = 0)
    {
        $ids = $request->input('ids');

        foreach ($ids as $i => $id) {
            Field::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('master.operated'));
    }

    /**
     * 禁用
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function disable(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        $field->disabled = BoolEnum::YES;
        $field->save();

        return $this->success(trans('master.updated'), route('navbar.field.index', [
            'navbar_id' => $field->navbar_id,
            'parent_id' => $field->parent_id,
        ]));
    }

    /**
     * 启用
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function enable(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        $field->disabled = BoolEnum::NO;
        $field->save();

        return $this->success(trans('master.updated'), route('navbar.field.index', [
            'navbar_id' => $field->navbar_id,
            'parent_id' => $field->parent_id,
        ]));
    }

    /**
     * 字段设置
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     * @author Chen Lei
     * @date 2021-02-03
     */
    public function settings(Request $request)
    {
        // 当前字段的初始化数据和选择的字段类型数据
        $this->field = array_object($request->field);

        $type = $this->field->type ?? null;
        $view = $type ? Field::types($type, 'view') : null;

        // 渲染视图
        if ($view = Arr::wrap($view)) {

            if (count($view) == 1) {
                return $this->view(reset($view));
            }

            return $this->view()->with('view', $view);
        }

        return null;
    }

    /**
     * 新建
     *
     * @param int $navbarId
     * @param int $parentId
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function create(int $navbarId = 0, int $parentId = 0)
    {
        $this->title = trans('navbar::field.create');
        $this->navbar_id = $navbarId;
        $this->parent_id = $parentId;

        $this->navbar = Navbar::find($navbarId);
        $this->parents = $parentId ? Item::find($parentId)->parents : [];

        $this->field = Field::findOrNew(0);
        $this->field->navbar_id = $navbarId;
        $this->field->parent_id = $parentId;
        $this->field->sort = Field::where('navbar_id', $navbarId)->where('parent_id', $parentId)->max('sort') + 1;
        $this->field->disabled = BoolEnum::NO;

        return $this->view();
    }

    /**
     * 保存
     *
     * @param \Modules\Navbar\Http\Requests\Admin\FieldRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function store(FieldRequest $request)
    {
        $field = new Field;
        $field->fill($request->all());
        $field->save();

        return $this->success(trans('master.updated'), route('navbar.field.index', [
            'navbar_id' => $request->navbar_id,
            'parent_id' => $request->parent_id,
        ]));
    }

    /**
     * 显示
     *
     * @param int $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function show(int $id)
    {
        $this->title = trans('navbar::field.show');

        $this->field = Field::findOrFail($id);

        return $this->view();
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->title = trans('navbar::field.edit');

        $this->field = Field::findOrFail($id);

        $this->navbar = Navbar::find($this->field->navbar_id);
        $this->parents = $this->field->parent_id ? Item::find($this->field->parent_id)->parents : [];

        return $this->view();
    }

    /**
     * 更新
     *
     * @param \Modules\Navbar\Http\Requests\Admin\FieldRequest $request
     * @param $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function update(FieldRequest $request, $id)
    {
        $field = Field::findOrFail($id);
        $field->fill($request->all());
        $field->save();

        return $this->success(trans('master.updated'), route('navbar.field.index', [
            'navbar_id' => $field->navbar_id,
            'parent_id' => $field->parent_id,
        ]));
    }

    /**
     * 删除
     *
     * @param int $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function destroy(int $id)
    {
        $field = Field::findOrFail($id);
        $field->delete();

        return $this->success(trans('master.updated'), route('navbar.field.index', [
            'navbar_id' => $field->navbar_id,
            'parent_id' => $field->parent_id,
        ]));
    }
}
