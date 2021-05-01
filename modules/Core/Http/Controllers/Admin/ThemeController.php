<?php

namespace Modules\Core\Http\Controllers\Admin;

use Zotop\Modules\Routing\AdminController as Controller;
use Zotop\Themes\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Modules\Core\Support\FileBrowser;

class ThemeController extends Controller
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($type='')
    {
        $this->title       = trans('core::theme.title');
        $this->description = trans('core::theme.description');

        $this->themes      = Theme::all();

        return $this->view();
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($theme='')
    {
        if ($theme) {
            Artisan::call("theme:publish",[
                'theme' => $theme
            ]);
        } else {
            Artisan::call('theme:publish');
        }

        return $this->success(trans('core::theme.publish.success'));
    }

    /**
     * 上传主题
     *
     * @return Response
     */
    public function upload()
    {

    }

    /**
     * 删除主题
     *
     * @return Response
     */
    public function delete(Request $request, $theme)
    {
        $theme = Theme::findOrFail($theme);
        $theme->delete();

        return $this->success(trans('master.deleted'), $request->referer());
    }
}
