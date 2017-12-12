<?php
namespace Modules\Media\Hook;

use Route;
use Auth;
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
     * @param  array $return  返回给前端的文件信息
     * @param  object $splFile 文件
     * @param  array $params  参数
     * @return array
     */
    public function upload($return, $splFile, $params)
    {
        if ($return['state']) {       
            
            // 合并信息
            $fileinfo = array_merge($params, $return, [
                'user_id' => Auth::user()->id,
                'token'   => Auth::user()->token
            ]);

            // 保存文件信息
            $file = new File;
            $file->fill($fileinfo);
            $file->save();

            return $return + ['id'=>$file->id];
        }
        
        return $result;
    }
}
