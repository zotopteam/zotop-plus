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
            $content = include($file);
            $file->itemcount = count($content);
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
        $name = $request->input('name');
        $name = trim(strtolower($name), '.php');

        if (! preg_match("/^[a-z0-9]+$/", $name)) {
            return $this->error(trans('developer::translate.filename.error'));
        }

        $name = $name.'.php';
        $module = module($module);
        $locale = config('app.locale');
        $path   = $module->getExtraPath('Resources/lang/'.$locale);
        $file   = $path.'/'.$name;

        if (File::exists($file)) {
            return $this->error(trans('core::master.existed', [$name]));
        }

        if (! File::isDirectory($path)) {
            File::makeDirectory($dir, 0775, true);
        }

        File::put($file, "<?php\r\nreturn [];");

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
        $module    = module($module);
        $locale    = config('app.locale');
        $filename  = $request->input('filename');
        
        $languages = Module::data('core::config.languages');

        foreach ($languages as $lang => $name) {
            $filepath  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);

            if (File::exists($filepath)) {
                File::delete($filepath);
            }            
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
        $maxlength = $request->input('maxlength', 20);
        $locale    = config('app.locale');
        $module    = module($module);

        $newline = "\r\n";

        foreach ($langs as $lang => $data) {

            $filepath  = $module->getExtraPath('Resources/lang/'.$lang.'/'.$filename);

            $content = '<?php'.$newline;
            $content .= 'return ['.$newline;

            foreach ($data as $key => $value) {
                
                // 如果语言值为空且不是主语言，跳过该项，主语言保留，其他不保留该条目
                if (empty($value) && $lang != $locale) {
                    continue;
                }

                $content .= "\t".str_pad("'".$key."'", $maxlength + 2, "  ")." => '".$value."',".$newline;
            }

            $content .= '];';

            // 自动生成目录
            if (! File::isDirectory($dir = dirname($filepath))) {
                File::makeDirectory($dir, 0775, true);
            }

            File::put($filepath, $content);
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
        $key = $request->input('key');
        $key = strtolower($key);
        $key = trim($key, '.');

        if (! preg_match("/^[a-z0-9.]+$/", $key)) {
            return $this->error(trans('developer::translate.key.error'));
        }

        // 确定最大长度，用于对齐
        $maxlength = $request->input('maxlength');
        if (strlen($key) > $maxlength) {
            $maxlength = strlen($key);
        }

        $filename = $request->input('filename');
        $module   = module($module);
        $locale   = config('app.locale');
        $filepath = $module->getExtraPath('Resources/lang/'.$locale.'/'.$filename);

        if (File::exists($filepath)) {
            $data = include($filepath);
            $data = is_array($data) ? $data : [];

            if (isset($data[$key])) {
                return $this->error(trans('developer::translate.key.existed'));
            }

            // 插入一个空值
            $data[$key] = '';

            $newline = "\r\n";
            $content = '<?php'.$newline;
            $content .= 'return ['.$newline;

            foreach ($data as $key => $value) {
                $content .= "\t".str_pad("'".$key."'", $maxlength + 2, "  ")." => '".$value."',".$newline;
            }

            $content .= '];';

            File::put($filepath, $content);

            return $this->success(trans('core::master.saved'), $request->referer());
        }

        return $this->error(trans('core::master.save.failed'));
    }

    /**
     * 删除键名
     * @param  Request $request
     * @param  string  $module 模块名称
     * @return json 
     */
    public function deletekey(Request $request, $module)
    {
        $key       = $request->input('key');
        $maxlength = $request->input('maxlength');
    }       
}
