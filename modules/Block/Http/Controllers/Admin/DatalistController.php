<?php

namespace Modules\Block\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Block\Models\Block;
use Modules\Block\Models\Datalist;

class DatalistController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('block::datalist.title');

        // 全部获取
        //$this->datalists = Datalist::all();
        // 部分获取
        //$this->datalists = Datalist::with('some')->where('key','value')->orderby('id','asc')->get();        
        // 分页获取
        //$this->datalists = Datalist::with('some')->where('key','value')->orderby('id','asc')->paginate(25);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create($block_id)
    {
        $this->title    = trans('block::datalist.create');
        $this->block    = Block::findOrFail($block_id);
        $this->fields   = Datalist::fields($this->block->fields);
        $this->datalist = Datalist::findOrNew(0);
        // 默认数据赋值
        $this->datalist->block_id = $this->block->id;


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
        $datalist = new Datalist;
        $datalist->fill($request->all());
        $datalist->save();

        return $this->success(trans('core::master.created'), route('block.datalist.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('block::datalist.show');

        $this->datalist = Datalist::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('block::datalist.edit');
        $this->id    = $id;
        $this->datalist = Datalist::findOrFail($id);

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
        $datalist = Datalist::findOrFail($id);
        $datalist->fill($request->all());        
        $datalist->save();

        return $this->success(trans('core::master.updated'), route('block.datalist.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $datalist = Datalist::findOrFail($id);
        $datalist->delete();

        return $this->success(trans('core::master.deleted'), route('block.datalist.index'));        
    }
}
