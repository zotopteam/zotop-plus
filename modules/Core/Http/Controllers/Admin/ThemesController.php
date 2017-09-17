<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Theme;
use Artisan;

class ThemesController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($type='')
    {
        $this->title       = trans('core::themes.title');
        $this->description = trans('core::themes.description');

        $this->themes      = Theme::getList($type);

        return $this->view();
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function files($name)
    {
        $this->title   = trans('core::themes.files');
        
        $this->folders = [];
        $this->files   = [];

        return $this->view()->with('theme',Theme::find($name));
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($name='')
    {
        if ($name) {
            Artisan::call("theme:publish",[
                'theme' => $name
            ]);
        } else {
            Artisan::call('theme:publish');
        }

        return $this->success(trans('core::themes.publish.success'));    
    }

    /**
     * 上传主题
     *
     * @return Response
     */
    public function upload()
    {
  
    }        
}
