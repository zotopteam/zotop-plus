<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Facades\Modules\Developer\Support\Lang;
use Module;
use File;

class TranslateController extends AdminController
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
        $this->module    = module($module);
        $this->locale    = config('app.locale');
        $this->languages = Module::data('core::config.languages');

        // 读取系统设置的主语言，翻译道其他语言
        $this->path      = $this->module->getExtraPath('Resources/lang/'.$this->locale);
        $this->files     = File::isDirectory($this->path) ? File::files($this->path) : [];
        $this->files     = collect($this->files)->transform(function($file) {
            $file->itemcount = Lang::get($file)->count();
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
        $name = Lang::fileName($request->input('name'));

        if (empty($name)) {
            return $this->error(trans('developer::translate.filename.error'));
        }

        $module = module($module);
        $locale = config('app.locale');
        $file   = $module->getExtraPath('Resources/lang/'.$locale.'/'.$name);

        if (Lang::exists($file)) {
            return $this->error(trans('core::master.existed', [$name]));
        }

        Lang::set($file, []);

        return $this->success(trans('core::master.saved'), $request->referer());
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

        $module    = module($module);

        // 删除时，删除全部语言下的该文件
        $languages = Module::data('core::config.languages');

        foreach ($languages as $lang => $name) {
            $file  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);
            Lang::delete($file);
        }

        return $this->success(trans('core::master.operated'), $request->referer());
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
        $this->module    = module($module);
        $this->locale    = config('app.locale');
        $this->languages = Module::data('core::config.languages');

        $this->filename  = $request->input('filename');
        $this->filepath  = $this->module->getExtraPath('Resources/lang/'.$this->locale.'/'.$this->filename);
        $this->prefix    = $this->module->getLowerName().'::'.File::name($this->filename).'.';

        $langs = [];

        foreach ($this->languages as $lang => $title) {
            $file = $this->module->getExtraPath('Resources/lang/'.$lang.'/'.$this->filename);
            $langs[$lang] = Lang::get($file);
        }

        $this->langs = $langs;
        $this->keys  = $langs[$this->locale]->keys();

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
        $locale    = config('app.locale');
        $module    = module($module);

        // 保存每种语言，如果不是locale，过滤空值
        foreach ($langs as $lang => $data) {
            $file  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);
            Lang::set($file, $data, ($lang != $locale));
        }

        return $this->success(trans('core::master.saved'), $request->referer());
    }

    /**
     * 新建键名
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function newkey(Request $request, $module)
    {
        $key = Lang::keyName($request->input('key'));

        if (empty($key)) {
            return $this->error(trans('developer::translate.key.error'));
        }

        $filename = $request->input('filename');
        $module   = module($module);
        $locale   = config('app.locale');
        $file     = $module->getExtraPath('Resources/lang/'.$locale.'/'.$filename);

        $langs = Lang::get($file);

        if ($langs->has($key)) {
            return $this->error(trans('developer::translate.key.existed'));
        }

        $data = $langs->merge([$key=>''])->toArray();

        Lang::set($file, $data);

        return $this->success(trans('core::master.saved'), $request->referer());
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

        // 获取module
        $module   = module($module);

        // 删除时，删除全部语言下的该项
        $languages = Module::data('core::config.languages');

        foreach ($languages as $lang => $name) {
            $file  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);
            Lang::forget($file, $key);
        }

        return $this->success(trans('core::master.deleted'), $request->referer());
    }       
}
