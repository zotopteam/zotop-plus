<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Zotop\Modules\Exceptions\FileExistedException;
use Zotop\Modules\Routing\AdminController;
use Artisan;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Module;

class CommandController extends AdminController
{

    /**
     *  获取配置数据
     *
     * @param string $key
     * @return mixed
     */
    private function commands($key, $subkey = null, $default = null)
    {
        static $commands = [];

        if (empty($commands)) {
            $commands = Module::data('developer::module.commands');
        }

        $key = $subkey ? $key . '.' . $subkey : $key;

        return Arr::get($commands, $key, $default);
    }


    /**
     * 命令列表
     *
     * @param Request $request
     * @param string $module 模块名称
     * @return mixed
     */
    public function index(Request $request, $module, $key)
    {
        $this->title = $this->commands($key, 'title');
        $this->dir = $this->commands($key, 'dir');
        $this->command = $this->commands($key, 'command');
        $this->help = $this->commands($key, 'help');

        $this->key = $key;
        $this->module = Module::findOrFail($module);
        $this->path = $this->module->getPath($this->dir);
        $this->files = File::isDirectory($this->path) ? File::allFiles($this->path) : [];

        return $this->view();
    }

    /**
     * 创建命令
     *
     * @param Request $request
     * @param string $module 模型名称
     * @return mixed
     */
    public function create(Request $request, $module, $key)
    {
        // 表单提交时
        if ($request->isMethod('POST')) {

            $name = $request->input('name');
            $command = $this->commands($key, 'command');

            $parameters = [
                'module' => $module,
                'name'   => $name,
            ];

            foreach ($this->commands($key, 'options', []) as $option => $view) {
                $parameters[$option] = $request->input($option);
            }

            try {
                Artisan::call($command, $parameters);
                return $this->success(trans('master.saved'), route('developer.command.index', [$module, $key]));
            } catch (FileExistedException $e) {
                return $this->error(trans('master.existed', [$name]));
            }

        }


        $this->title = trans('master.create');
        $this->module = Module::findOrFail($module);
        $this->key = $key;
        $this->options = $this->commands($key, 'options', []);
        $this->name_label = $this->commands($key, 'name.label');
        $this->name_pattern = $this->commands($key, 'name.pattern', '^[a-zA-Z][a-zA-Z0-9_-]+[a-zA-Z0-9]$');
        $this->name_help = $this->commands($key, 'name.help');


        return $this->view();
    }
}
