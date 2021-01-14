<?php

namespace Modules\Navbar\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\AdminController as Controller;
use Modules\Navbar\Models\Navbar;

class NavbarController extends Controller
{
    /**
     * 首页
     *
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->title = trans('navbar::navbar.title');

        $this->navbars = Navbar::paginate(25);

        // 全部获取
        //$this->navbars = Navbar::all();
        // 部分获取
        //$this->navbars = Navbar::with('some')->where('key','value')->orderby('id','asc')->get();        
        // 分页获取
        //$this->navbars = Navbar::with('some')->where('key','value')->orderby('id','asc')->paginate(25);

        return $this->view();
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

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        $navbar = new Navbar;
        $navbar->fill($request->all());
        $navbar->save();

        return $this->success(trans('master.created'), route('navbar.navbar.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
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
     * @param  Request $request
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function update(Request $request, $id)
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
        $navbar->delete();

        return $this->success(trans('master.deleted'), route('navbar.navbar.index'));        
    }
}
