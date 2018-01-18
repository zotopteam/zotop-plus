<?php
namespace Modules\Media\Hook;

use Route;
use Auth;
use Modules\Media\Models\Folder;
use Modules\Media\Models\File;

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

    /**
     * 上传控件扩展工具
     * 
     * @param  array $tools 工具数据
     * @param  array $params 传入参数
     * @return array
     */
    public function select($tools, $params)
    {
        @extract($params);

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
            ]
        ];

        return $select + $tools;
    }
}
