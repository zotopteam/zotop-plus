<?php

namespace Modules\Media\Hook;

use Modules\Media\Models\Media;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

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
                'parent_id'  => Request::input('folder_id', 0),
                'module'     => Request::input('module', null),
                'controller' => Request::input('controller', null),
                'action'     => Request::input('action', null),
                'field'      => Request::input('field', null),
                'source_id'  => Request::input('source_id', null),
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
    public function uploadTools($tools, $params)
    {
        $select = [
            'uploaded'   => [
                'text'   => trans('media::media.insert.from.uploaded'),
                'icon'   => 'fa fa-cloud',
                'href'   => route('media.select.uploaded', $params),
                'active' => Route::active('media.select.uploaded'),
            ],
            'libarary' => [
                'text'   => trans('media::media.insert.from.library'),
                'icon'   => 'fa fa-database',
                'href'   => route('media.select.library', $params),
                'active' => Route::active('media.select.library'),
            ],
        ];

        return array_merge($select, $tools);
    }
}
