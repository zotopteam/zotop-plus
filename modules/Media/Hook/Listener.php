<?php
namespace Modules\Media\Hook;

use Route;
use Auth;
use Modules\Media\Models\Media;

class Listener
{
    /**
     * 开始菜单扩展
     * 
     * @param  array $start 开始菜单数组
     * @return array
     */
    public function start($start)
    {
        $start['media'] = [
            'text' => trans('media::media.title'),
            'href' => route('media.index'),
            'icon' => 'fa fa-images bg-info text-white',
            'tips' => trans('media::media.description'),
        ];
        
        return $start;
    }

    public function navbar($navbar)
    {
        $navbar['media'] = [
            'text'   => trans('media::media.title'),
            'href'   => route('media.index'),
            'active' => Route::is('media.*')
        ];

        return $navbar;
    }

    /**
     * 监听上传
     * @param  array $data  返回给前端的文件信息
     * @param  object $upload 上传对象
     * @return array
     */
    public function upload($data, $upload)
    {
        if ($data['state']) {    
            
            // 合并信息
            $fileinfo = array_merge($data, [
                'parent_id' => $upload->request->input('folder_id', 0),
                'module' => $upload->request->input('module', null),
                'controller' => $upload->request->input('controller', null),
                'action' => $upload->request->input('action', null),
                'field' => $upload->request->input('field', null),
                'source_id' => $upload->request->input('source_id', null),
            ]);

            // 保存文件信息
            $media = new Media;
            $media->fill($fileinfo);
            $media->save();

            $data['media_id'] = $media->id;
        }
        
        return $data;
    }

    /**
     * 上传控件扩展工具
     * 
     * @param  array $tools 工具数据
     * @param  array $params 传入参数
     * @return array
     */
    public function select($tools, $params)
    {
        $typename = $params['typename'];

        // 删除core模块的目录选择
        unset($tools['dir']);

        $select = [
            'uploaded'   => [
                'text'   => trans('media::media.insert.from.uploaded',[$typename]),
                'icon'   => 'fa fa-cloud',
                'href'   => route('media.select.uploaded', $params),
                'active' => Route::active('media.select.uploaded'),
            ],
            'libarary' => [
                'text'   => trans('media::media.insert.from.library',[$typename]),
                'icon'   => 'fa fa-database',
                'href'   => route('media.select.library', [0] + $params),
                'active' => Route::active('media.select.library'),
            ],
            'dir' => [
                'text'   => trans('media::media.insert.from.dir',[$typename]),
                'icon'   => 'fa fa-folder',
                'href'   => route('media.select.dir', $params),
                'active' => Route::active('media.select.dir'),
            ]            
        ];

        return array_merge($tools, $select);
    }
}
