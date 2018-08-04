<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Support\FileBrowser;
use Theme;
use Artisan;
use File;

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
    public function files(Request $request, $theme)
    {
        $theme   = Theme::find($theme);

        $browser = app(FileBrowser::class, [
            'root' => path_base($theme->path),
        ]);

        $this->params   = $browser->params;
        $this->path     = $browser->path;
        $this->upfolder = $browser->upfolder();
        $this->position = $browser->position();
        $this->folders  = $browser->folders();
        $this->files    = $browser->files();

        $this->title    = trans('core::themes.files');

        return $this->view()->with('theme',$theme);
    }

    /**
     * 视图选择
     * 
     * @param  Request $request
     * @param  string  $type  类型
     * @return array
     */
    public function selectView(Request $request, $theme=null)
    {
        $theme  = $theme ? Theme::find($theme) : app('current.theme');
        $views  = path_base($theme->path).'/views';
        $module = strtolower($request->input('module'));

        $root   = $views.'/'.$module;

        $browser = app(FileBrowser::class, [
            'root' => $root,
            'dir'  => $request->input('dir')
        ]);

        $this->params   = $browser->params;
        $this->path     = $browser->path;
        $this->upfolder = $browser->upfolder();
        $this->position = $browser->position();
        $this->folders  = $browser->folders();
        $this->files    = $browser->files()->filter(function($item) use($request) {
            return $item->mime == 'text';
        })->each(function($item) use ($root, $module) {
            $view = str_after($item->path, $root.'/');
            $view = str_before($view, '.blade.php');
            $view = str_replace('/', '.', $view);
            $item->view = $module.'::'.$view;
            return $item;
        });

        debug($this->files);

        // 选择文件个数，默认只选择一个
        $this->select = $request->input('select', 1);

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
