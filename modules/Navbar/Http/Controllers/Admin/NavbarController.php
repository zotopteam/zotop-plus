<?php

namespace Modules\Navbar\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use App\Support\Enums\BoolEnum;
use Illuminate\Http\Request;
use Modules\Navbar\Http\Requests\Admin\NavbarRequest;
use Modules\Navbar\Models\Navbar;
use Modules\Navbar\Models\QueryFilters\NavbarFilter;

class NavbarController extends Controller
{
    /**
     * 首页
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Navbar\Models\QueryFilters\NavbarFilter $filter
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, NavbarFilter $filter)
    {
        $this->title = trans('navbar::navbar.title');

        $this->navbars = Navbar::withCount('item')->filter($filter)->get();

        return $this->view();
    }

    /**
     * 排序
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-31
     */
    public function sort(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $i => $id) {
            Navbar::where('id', $id)->update(['sort' => $i]);
        }

        return $this->success(trans('master.operated'), route('navbar.navbar.index'));
    }

    /**
     * 禁用
     *
     * @param \Modules\Navbar\Http\Requests\Admin\NavbarRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function disable(NavbarRequest $request, $id)
    {
        $navbar = Navbar::findOrFail($id);
        $navbar->disabled = BoolEnum::YES;
        $navbar->save();

        return $this->success(trans('master.updated'), route('navbar.navbar.index'));
    }

    /**
     * 启用
     *
     * @param \Modules\Navbar\Http\Requests\Admin\NavbarRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function enable(NavbarRequest $request, $id)
    {
        $navbar = Navbar::findOrFail($id);
        $navbar->disabled = BoolEnum::NO;
        $navbar->save();

        return $this->success(trans('master.updated'), route('navbar.navbar.index'));
    }

    /**
     * 新建
     *
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->title = trans('navbar::navbar.create');

        $this->navbar = Navbar::findOrNew(0);
        $this->navbar->sort = Navbar::max('sort') + 1;
        $this->navbar->disabled = BoolEnum::NO;

        return $this->view();
    }

    /**
     * 保存
     *
     * @param \Modules\Navbar\Http\Requests\Admin\NavbarRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function store(NavbarRequest $request)
    {
        $navbar = new Navbar;
        $navbar->fill($request->all());
        $navbar->save();

        return $this->success(trans('master.created'), route('navbar.navbar.index'));
    }

    /**
     * 显示
     *
     * @param int $id
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $this->title = trans('navbar::navbar.show');

        $this->navbar = Navbar::findOrFail($id);

        return $this->view();
    }

    /**
     * 编辑
     *
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->title = trans('navbar::navbar.edit');

        $this->navbar = Navbar::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param \Modules\Navbar\Http\Requests\Admin\NavbarRequest $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function update(NavbarRequest $request, $id)
    {
        $navbar = Navbar::findOrFail($id);
        $navbar->fill($request->all());
        $navbar->save();

        return $this->success(trans('master.updated'), route('navbar.navbar.index'));
    }

    /**
     * 删除
     *
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function destroy($id)
    {
        $navbar = Navbar::findOrFail($id);

        if ($navbar->item->count()) {
            return $this->error(trans('navbar::navbar.destroy.forbidden'));
        }

        $navbar->delete();

        return $this->success(trans('master.deleted'), route('navbar.navbar.index'));
    }
}
