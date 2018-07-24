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
     * 历史记录
     *
     * @return Response
     */
    public function history($block_id)
    {
        $this->title = trans('block::datalist.history');
        $this->block = Block::findOrFail($block_id);

        // 分页获取
        $this->datalists = Datalist::where('status','history')->paginate(25);

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
        $this->datalist = Datalist::findOrNew(0);

        // 默认数据赋值
        $this->datalist->block_id = $this->block->id;

        // 数据字段
        $this->fields   = Datalist::fields($this->block->fields, [
            'data_id' => 'block-'.$this->block->id
        ]);

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

        return $this->success(trans('core::master.created'), route('block.datalist.index', $request->input('block_id')));
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
        $this->block    = Block::findOrFail($this->datalist->block_id);

        // 数据字段
        $this->fields   = Datalist::fields($this->block->fields, [
            'data_id' => 'block-'.$this->block->id
        ]);     

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

        return $this->success(trans('core::master.updated'), route('block.datalist.index', $request->input('block_id')));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $datalist = Datalist::findOrFail($id);

        if ($datalist->status == 'publish') {
            $datalist->status = 'history';     
            $datalist->save();
            return $this->success(trans('block::datalist.publish.deleted'), $request->referer());
        }

        if ($datalist->status == 'history') {
            $datalist->delete();
            return $this->success(trans('block::datalist.history.deleted'), $request->referer()); 
        }

        return $this->error(trans('core::master.operate.failed'));      
    }

    /**
     * 排序
     *
     * @return Response
     */
    public function sort(Request $request)
    {
        $block_id = $request->input('block_id');
        $sort     = $request->input('sort');

        // 排序字段sort是逆向排序
        $sort = array_reverse($sort);

        foreach ($sort as $i => $id) {
            Datalist::where('id', $id)->update(['sort' => $i+1]);
        }

        Datalist::updateBlockData($block_id);

        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 排序
     *
     * @return Response
     */
    public function stick(Request $request, $id, $stick)
    {
        Datalist::where('id', $id)->update([
            'stick' => $stick
        ]);

        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 重新发布
     * 
     * @param  int $id 数据编号
     * @return json
     */
    public function republish($id)
    {
        // 重新发布：设置状态为发布，并且将数据放在最前面，最下面如果有超出条数限制部分，自动推进历史记录
        $datalist = Datalist::findOrFail($id);
        $datalist->status = 'publish';
        $datalist->sort = Datalist::publish($datalist->block_id)->pluck('sort')->max() + 1;
        $datalist->save();

        return $this->success(trans('core::master.operated'), route('block.data', $datalist->block_id));

    }       
}
