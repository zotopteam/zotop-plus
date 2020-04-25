<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Modules\Facades\Module;
use App\Modules\Maker\Lang;
use App\Modules\Routing\AdminController as Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


class TranslateController extends Controller
{
    /**
     * 首页
     * 
     * @param  Request $request 
     * @param  string  $module 模块名称
     * @return Response
     */
    public function index(Request $request, $module)
    {
        $this->title     = trans('developer::translate.title');
        $this->locale    = config('app.locale');        
        $this->module    = Module::findOrFail($module);
        $this->languages = Module::data('core::config.languages');

        // 读取系统设置的主语言，翻译道其他语言
        $this->path      = $this->module->getPath('lang', true) . DIRECTORY_SEPARATOR . $this->locale;
        $this->files     = File::isDirectory($this->path) ? File::allFiles($this->path) : [];
        $this->files     = collect($this->files)->transform(function($file) {
            $file->itemcount = count(include($file));
            return $file;
        });

        return $this->view();
    }

    /**
     * 新建翻译文件
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function newfile(Request $request, $module)
    {
        $name   = $request->input('name');
        $locale = config('app.locale');
        $lang   = Lang::instance($module, $locale)->name($name);

        if ($lang->exists()) {
            return $this->error(trans('master.existed', [$name]));
        }

        $lang->data([])->save();

        return $this->success(trans('master.saved'), $request->referer());
    }

    /**
     * 删除翻译文件
     * 
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function deleteFile(Request $request, $module)
    {
        $filename  = $request->input('filename');

        // 删除时，删除全部语言下的该文件
        $languages = Module::data('core::config.languages');

        foreach ($languages as $lang => $name) {
            Lang::instance($module, $lang)->name($filename)->delete();
        }

        return $this->success(trans('master.operated'), $request->referer());
    }

    /**
     * 翻译详情
     * @param  Request $request 
     * @param  string  $module 模块名称
     * @return Response
     */
    public function translate(Request $request, $module)
    {

        $this->title     = trans('developer::translate.translate');
        $this->locale    = config('app.locale');
        $this->module    = Module::findOrFail($module);
        $this->languages = Module::data('core::config.languages');

        $this->filename  = $request->input('filename');
        $this->filepath  = Lang::instance($module, $this->locale)->name($this->filename)->getPath();
        $this->prefix    = $this->module->getLowerName().'::'.File::name($this->filename);

        // 获取全部语言的翻译
        $langs = [];
        foreach ($this->languages as $lang => $title) {
            $langs[$lang] = Lang::instance($module, $lang)->name($this->filename)->get();
        }

        $this->langs = $langs;
        $this->keys  = array_keys($langs[$this->locale]);

        // 是否开启翻译
        if (Route::has('translator.translate')) {
            $this->translator = true;
        } else {
            $this->translator = false;
        }

        return $this->view();
    }

    /**
     * 保存翻译
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function save(Request $request, $module)
    {
        $langs     = $request->input('langs', []);
        $filename  = $request->input('filename');

        // 保存每种语言，如果不是locale，过滤空值
        foreach ($langs as $lang => $data) {
            Lang::instance($module, $lang)->name($filename)->data($data)->save(true);
        }

        return $this->success(trans('master.saved'), $request->referer());
    }

    /**
     * 新建键名
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function newkey(Request $request, $module)
    {
        $key      = $request->input('key');
        $filename = $request->input('filename');
        $locale   = config('app.locale');
        $lang     = Lang::instance($module, $locale)->name($filename);

        if ($lang->has($key)) {
            return $this->error(trans('developer::translate.key.existed'));
        }

        $lang->set($key, '');

        return $this->success(trans('master.saved'), $request->referer());
    }

    /**
     * 删除键名
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function deletekey(Request $request, $module)
    {
        $filename = $request->input('filename');
        $key      = $request->input('key');

        // 删除时，删除全部语言下的该项
        $languages = Module::data('core::config.languages');

        foreach ($languages as $lang => $name) {
            Lang::instance($module, $lang)->name($filename)->forget($key);
        }

        return $this->success(trans('master.deleted'), $request->referer());
    }       
}
