<?php

namespace Modules\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Support\FileBrowser;
use Theme;
use Artisan;
use File;

class SiteController extends AdminController
{
    /**
     * 视图选择
     * 
     * @param  Request $request
     * @param  string  $type  类型
     * @return array
     */
    public function selectView(Request $request, $theme=null)
    {
        $views  = path_base(Theme::path($theme.':views'));
        $module = strtolower($request->input('module'));

        // 根目录
        $root   = $views.'/'.$module;

        // 浏览文件
        $browser = app(FileBrowser::class, [
            'root'       => $root,
            'dir'        => $request->input('dir'),
            'autoCreate' => true
        ]);

        $this->params   = $browser->params;
        $this->path     = $browser->path;
        $this->upfolder = $browser->upfolder();
        $this->position = $browser->position();
        $this->folders  = $browser->folders();
        $this->files    = $browser->files()->filter(function($item) use($request) {
            return $item->mime == 'text';
        })->each(function($item) use ($root, $module) {
            
            // 获取视图
            $view = str_after($item->path, $root.'/');
            $view = str_before($view, '.blade.php');
            $view = str_replace('/', '.', trim($view, '/'));
            $item->view = $module.'::'.$view;

            return $item;
        });

        debug($this->files);

        // 选择文件个数，默认只选择一个
        $this->select = $request->input('select', 1);

        return $this->view();
    }
}
