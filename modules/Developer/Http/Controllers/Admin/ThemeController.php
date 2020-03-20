<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use App\Themes\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Modules\Core\Support\FileBrowser;
use Modules\Developer\Rules\ThemeName;

class ThemeController extends Controller
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('developer::theme.title');
        $this->themes = Theme::all();

        return $this->view();
    }

    /**
     * 文件管理
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

        $this->title    = trans('developer::theme.files');

        return $this->view()->with('theme',$theme);
    }

    /**
     * 主题创建
     * @return Response
     */
    public function create()
    {
        $this->title = trans('developer::theme.create');

        return $this->view();
    }

    /**
     * 主题生成
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', new ThemeName()],
        ]);

        Artisan::call('theme:make', [
            'theme'   => $request->input('name'),
            '--type' => $request->input('type'),
        ]);

        return $this->success(trans('master.created'), route('developer.theme.index'));     
    }   
}
