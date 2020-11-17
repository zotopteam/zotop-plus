<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Modules\Routing\AdminController;
use App\Modules\Exceptions\ClassExistedException;


class MigrationController extends AdminController
{
    /**
     * 命令列表
     *
     * @param Request $request
     * @param string $module 模块名称
     * @return mixed
     */
    public function index(Request $request, string $module)
    {
        $this->title = trans('developer::migration.title');
        $this->module = Module::findorFail($module);
        $this->path = $this->module->getPath('migration', true);
        $this->files = File::isDirectory($this->path) ? File::allFiles($this->path) : [];
        $this->migrations = \DB::table('migrations')->get()->pluck('migration')->toArray();

        return $this->view();
    }

    /**
     * 创建命令
     *
     * @param Request $request
     * @param string $module 模型名称
     * @return mixed
     */
    public function create(Request $request, $module)
    {
        // 表单提交时
        if ($request->isMethod('POST')) {

            $name = $request->input('name');
            $command = $request->input('command');

            try {

                Artisan::call($command, [
                    'module' => $module,
                    'name'   => $name,
                ]);

                return $this->success(trans('master.created'), route('developer.migration.index', [$module]));
            } catch (ClassExistedException $e) {
                return $this->error(trans('master.existed', [$name]));
            }
        }

        $this->module = Module::findorFail($module);
        $this->title = trans('developer::migration.create');

        return $this->view();
    }

    /**
     * 执行命令
     *
     * @param Request $request
     * @param string $module 模型名称
     * @return mixed
     */
    public function execute(Request $request, $module, $action)
    {
        $actions = [
            'migrate'  => 'module:migrate',
            'rollback' => 'module:migrate-rollback',
            'reset'    => 'module:migrate-reset',
            'refresh'  => 'module:migrate-refresh',
            'seed'     => 'module:seed',
        ];

        $command = $actions[$action] ?? reset($actions);

        Artisan::call($command, [
            'module'  => $module,
            '--force' => true,
        ]);

        return $this->success(trans('master.operated'), route('developer.migration.index', [$module]));
    }

    public function migrateFile(Request $request, $module, $action)
    {
        Artisan::call('migrate:files', [
            'files'   => $request->input('file'),
            '--mode'  => $action,
            '--force' => true,
        ]);

        return $this->success(trans('master.operated'), route('developer.migration.index', [$module]));
    }
}
