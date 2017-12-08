<?php
namespace Modules\Media\Hook;

use Route;
use Modules\Media\Models\Folder;
use Modules\Media\Models\File;


class Listener
{
    /**
     * [start description]
     * @param  [type] $start [description]
     * @return [type]        [description]
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
     * 
     * @return array info
     */
    public function upload($info, $file = null, $params = [])
    {
        $fileinfo = array_merge($params, $info);

        // 图片文件处理
        if ($fileinfo['type'] == 'image') {
            // 图片缩放
            // ……
            // 图片水印
            // ……
            // 补全图片宽高
            if (empty($fileinfo['width']) || empty($fileinfo['height'])) {
                $imageinfo = getimagesize($file->getRealPath()) ?? [0,0];
                $fileinfo['width']  = $imageinfo[0];
                $fileinfo['height'] = $imageinfo[1];
            }
        }

        // 保存文件信息
        File::create($fileinfo);

        return $info;
    }
}
