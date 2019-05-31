<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Module;
use File;

class TranslateController extends AdminController
{
    /**
     * 首页
     *
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

        return $this->view();
    }

    public function translate(Request $request, $module)
    {

        $this->title     = trans('developer::translate.translate');
        $this->module    = module($module);
        $this->locale    = config('app.locale');
        $this->languages = Module::data('core::config.languages');

        $this->filename  = $request->input('filename');
        $this->filepath  = $this->module->getExtraPath('Resources/lang/'.$this->locale.'/'.$this->filename);

        $langs = [];

        foreach ($this->languages as $lang => $title) {
            $filepath = $this->module->getExtraPath('Resources/lang/'.$lang.'/'.$this->filename);
            if (file_exists($filepath)) {
                $langs[$lang] = include($filepath);
            } else {
                $langs[$lang] = [];
            }
        }

        $this->langs = $langs;
        $this->keys = collect(array_keys($langs[$this->locale]));

        // 获取key的最大长度，用于保存是对齐
        $this->maxlength = $this->keys->map(function($key) {
            return strlen($key);
        })->max();

        return $this->view();
    }

    public function save(Request $request, $module)
    {
        $langs     = $request->input('langs', []);
        $filename  = $request->input('filename');
        $maxlength = $request->input('maxlength', 20);
        $module    = module($module);

        $newline = "\r\n";

        foreach ($langs as $lang => $data) {

            $filepath  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);

            $content = '<?php'.$newline;
            $content .= 'return ['.$newline;

            foreach ($data as $key => $value) {
                
                if (empty($value)) {
                    continue;
                }

                $content .= "\t".str_pad("'".$key."'", $maxlength + 2, "  ")." => '".$value."',".$newline;
            }

            $content .= '];'.$newline;

            // 自动生成目录
            if (! File::isDirectory($dir = dirname($filepath))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($filepath, $content);
        }

        return $this->success(trans('core::master.saved'), $request->referer());
    }
}
