<?php

namespace Modules\Navbar\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use App\Support\Enums\BoolEnum;
use Illuminate\Http\Request;
use Modules\Navbar\Http\Requests\Admin\ItemRequest;
use Modules\Navbar\Models\Item;
use Modules\Navbar\Models\Navbar;
use Modules\Navbar\Models\QueryFilters\ItemFilter;

class ItemController extends Controller
{
    /**
     * 首页
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Navbar\Models\QueryFilters\ItemFilter $filter
     * @param int $navbarId
     * @param int $parentId
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, ItemFilter $filter, int $navbarId = 0, int $parentId = 0)
    {
        $this->title = trans('navbar::item.title');

        $this->navbar_id = $navbarId;
        $this->parent_id = $parentId;
        $this->navbar = Navbar::find($navbarId);
        $this->parents = $parentId ? Item::find($parentId)->parents : [];

        // 分页获取
        $this->items = Item::withCount('child')
            ->filter($filter)
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
            Navbar::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('master.operated'));
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
        $this->title = trans('navbar::item.create');

        $this->navbar_id = $navbarId;
        $this->parent_id = $parentId;
        $this->parents = $parentId ? Item::find($parentId)->parents : [];

        $this->item = Item::findOrNew(0);
        $this->item->navbar_id = $navbarId;
        $this->item->parent_id = $parentId;
        $this->item->sort = Item::where('navbar_id', $navbarId)->where('parent_id', $parentId)->max('sort') + 1;
        $this->item->disabled = BoolEnum::NO;

        return $this->view();
    }

    /**
     * 保存
     *
     * @param \Modules\Navbar\Http\Requests\Admin\ItemRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function store(ItemRequest $request)
    {
        $item = new Item;
        $item->fill($request->all());
        $item->save();

        return $this->success(trans('master.created'), route('navbar.item.index', [
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
        $this->title = trans('navbar::item.show');

        $this->item = Item::findOrFail($id);

        return $this->view();
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $this->title = trans('navbar::item.edit');

        $this->id = $id;
        $this->item = Item::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param \Modules\Navbar\Http\Requests\Admin\ItemRequest $request
     * @param int $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function update(ItemRequest $request, int $id)
    {
        $item = Item::findOrFail($id);
        $item->fill($request->all());
        $item->save();

        return $this->success(trans('master.updated'), route('navbar.item.index', [
            'navbar_id' => $request->navbar_id,
            'parent_id' => $request->parent_id,
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
        $item = Item::findOrFail($id);

        if ($item->child->count()) {
            return $this->error(trans('navbar::item.destroy.forbidden'));
        }

        $item->delete();

        return $this->success(trans('master.deleted'), route('navbar.item.index', [
            'navbar_id' => $item->navbar_id,
            'parent_id' => $item->parent_id,
        ]));
    }
}
