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
        $this->datalists = Datalist::with('user')->where('block_id',$block_id)->where('status','history')->paginate(25);

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
     * @return json
     */
    public function store(Request $request)
    {
        $datalist = new Datalist;
        $datalist->fill($request->all());
        $datalist->save();

        return $this->success(trans('core::master.created'), route('block.datalist.index', $request->input('block_id')));
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
     * @return json
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
     * @return json
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
     * @return json
     */
    public function sort(Request $request, $block_id)
    {
        $id    = $request->input('id');
        $sort  = $request->input('sort');
        $stick = $request->input('stick');

        // 将当前列表 $sort 之前的数据的 sort 全部加 1， 为拖动的数据保留出位置
        Datalist::where('block_id', $block_id)->where('sort','>=', $sort)->increment('sort', 1);        

        // 更新当前数据的排序和置顶信息，如果排在置顶数据之前，自动置顶，如果排在非置顶数据后，自动取消置顶
        Datalist::where('id', $id)->update([
            'sort'  => $sort,
            'stick' => $stick,
        ]);
        
        // 更新区块数据
        Datalist::updateBlockData($block_id);

        return $this->success(trans('core::master.operated'), $request->referer());
    }

    /**
     * 排序
     *
     * @return json
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
        // 设置状态为发布，并且将数据放在最前面，最下面如果有超出条数限制部分，自动推进历史记录
        // 设置置顶状态为 非置顶：0
        $datalist = Datalist::findOrFail($id);
        $datalist->status = 'publish';
        $datalist->sort = time();
        $datalist->stick = 0;
        $datalist->save();

        return $this->success(trans('core::master.operated'), route('block.data', $datalist->block_id));
    }       
}
