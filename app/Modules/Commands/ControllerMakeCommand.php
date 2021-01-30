<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use App\Modules\Maker\OptionTableTrait;
use App\Modules\Maker\OptionTypeTrait;
use Illuminate\Support\Str;

class ControllerMakeCommand extends GeneratorCommand
{
    use OptionTableTrait, OptionTypeTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller
                {module : The module to use}
                {name? : The name to use}
                {--table= : The table to use.}
                {--type=frontend : The type of controller,[backend|frontend|api].}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = 'Controller';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var null
     */
    protected $dirKey = 'controller';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'controller';

    /**
     * Execute the console command.
     *
     * @return bool|null|void
     * @throws \App\Modules\Exceptions\FileExistedException
     * @author Chen Lei
     * @date 2020-11-20
     */
    public function handle()
    {
        // name 为可选值，如果没有输入name，则必须输入--table
        $this->requireTableOrName();

        parent::handle();
    }

    /**
     * 重载prepare
     *
     * @return boolean
     */
    protected function prepare()
    {
        $this->stub = $this->stub . '/' . $this->getTypeInput();

        // 替换变量
        $this->replace([
            'controller_lower_name' => $this->getControllerLowerName(),
        ]);

        if ($this->isResource()) {
            $this->prepareResource();
        } else {
            $this->preparePlain();
        }

        return true;
    }

    /**
     * 生成完成后执行
     *
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    protected function generated()
    {
        $this->generateLangFile();

        if ($this->isResource()) {
            $this->generatedResource();
        } else {
            $this->generatedPlain();
        }
    }


    /**
     * 是否为资源控制器
     *
     * @return bool
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function isResource()
    {
        // 如果附加了表则为资源控制器
        return $this->getTableName() ? true : false;
    }

    /**
     * 创建表依赖的资源控制器
     *
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function prepareResource()
    {
        $this->stub = $this->stub . '/resource';

        $this->replace([
            'model_base_name'   => $this->getModelBaseName(),
            'model_full_name'   => $this->getModelFullName(),
            'model_list'        => $this->getModelList(),
            'model_var'         => $this->getModelName(),
            'filter_base_name'  => $this->getFilterBaseName(),
            'filter_full_name'  => $this->getFilterFullName(),
            'request_base_name' => $this->getRequestBaseName(),
            'request_full_name' => $this->getRequestFullName(),
        ]);
    }

    /**
     * 资源控制器生成完成后执行
     *
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function generatedResource()
    {
        // 创建请求验证
        $this->call('module:make-model', [
            'module'  => $this->getModuleName(),
            '--table' => $this->getTableName(),
            '--force' => $this->option('force'),
        ]);

        // 创建请求验证
        $this->call('module:make-request', [
            'module'  => $this->getModuleName(),
            'name'    => $this->getLowerNameInput(),
            '--table' => $this->getTableName(),
            '--type'  => $this->getTypeInput(),
            '--force' => $this->option('force'),
        ]);

        // 创建请求验证
        $this->call('module:make-filter', [
            'module'  => $this->getModuleName(),
            'name'    => $this->getLowerNameInput(),
            '--table' => $this->getTableName(),
            '--force' => $this->option('force'),
        ]);

        foreach (['index', 'create', 'edit', 'show'] as $action) {
            $this->generateView($action, $this->option('force'));
        }
    }

    /**
     * 创建简单控制器
     *
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function preparePlain()
    {
        $this->stub = $this->stub . '/plain';
    }

    /**
     * 简单控制器生成完成后执行
     *
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function generatedPlain()
    {
        $this->generateView('index', $this->option('force'));
    }

    /**
     * 生成语言文件
     *
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function generateLangFile()
    {
        // 获取首字母大写的名称
        $name = $this->getStudlyNameInput();

        // 语言文件内容
        $lang = [
            'title'       => $name,
            'description' => $name,
        ];

        if ($this->isResource()) {
            $lang = array_merge($lang, [
                'create' => "Create {$name}",
                'edit'   => "Edit {$name}",
                'show'   => "Show {$name}",
            ]);
        }

        // 如果存在表，自动创建表的语言字段
        if ($this->getTableName()) {
            $lang = array_merge($lang, $this->getTableColumnLang());
        }

        // 生成语言文件
        $this->generateArrayLang($this->getLowerNameInput(), $lang, $this->option('force'));
    }

    /**
     * 获取输入的 model
     *
     * @return string
     */
    protected function getModelName()
    {
        return $this->getTableShortName();
    }

    /**
     * 获取模型的基本类名
     *
     * @return string
     */
    protected function getModelBaseName()
    {
        return Str::studly($this->getModelName());
    }

    /**
     * 获取模型的完整类名
     *
     * @return string
     */
    protected function getModelFullName()
    {
        return $this->getDirNamespace('model') . '\\' . $this->getModelBaseName();
    }

    /**
     * 获取模型的复数名词
     *
     * @return string
     */
    protected function getModelList()
    {
        return Str::plural($this->getModelName());
    }

    /**
     * 获取控制器名称小写格式，不含Controller
     *
     * @return string
     */
    protected function getControllerLowerName()
    {
        return Str::replaceLast(strtolower($this->appendName), '', strtolower($this->getClassName()));
    }

    /**
     * 获取滤器基本名称
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getFilterBaseName()
    {
        return $this->getStudlyNameInput() . 'Filter';
    }

    /**
     * 获取滤器完整名称
     *
     * @return string
     * @throws \Exception
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getFilterFullName()
    {
        return $this->getDirNamespace('filter') . '\\' . $this->getFilterBaseName();
    }

    /**
     * 获取验证基本名称
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getRequestBaseName()
    {
        return $this->getStudlyNameInput() . 'Request';
    }

    /**
     * 获取滤器完整名称
     *
     * @return string
     * @throws \Exception
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getRequestFullName()
    {
        return $this->getClassNamespace('request') . '\\' . $this->getRequestBaseName();
    }

    /**
     * 表的字段语言翻译
     *
     * @return array
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getTableColumnLang()
    {
        $lang = [];

        $this->getTableColumns()->reject(function ($column) {
            return $column['increments'] == 1 || in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        })->each(function ($column) use (&$lang) {
            $name = $column['name'];
            $comment = $column['comment'] ?: Str::studly($name);
            $lang["{$name}.label"] = $comment;
            $lang["{$name}.help"] = $comment;
        });

        return $lang;
    }

    /**
     * 获取表的列视图
     *
     * @param $stub
     * @return string
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getTableColumnsView($stub)
    {
        $view = [];
        $stub = $this->stub . '/' . $stub;

        $this->getTableColumns()->reject(function ($column) {
            return $column['increments'] == 1 || in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        })->each(function ($column) use (&$view, $stub) {
            $this->replace([
                'column_name'     => $column['name'],
                'column_required' => $column['nullable'] ? '' : 'required',
                'column_field'    => $this->getTableColumnsField($column),
            ]);
            $view[] = $this->renderStub($stub);
        });

        return implode(PHP_EOL, $view);
    }

    /**
     * 获取表单的字段标签
     *
     * @param array $column
     * @author Chen Lei
     * @date 2021-01-29
     */
    protected function getTableColumnsField(array $column)
    {
        $attributes = [
            'type' => 'text',
            'name' => $column['name'],
        ];

        if ($column['nullable'] == 0) {
            $attributes['required'] = 'required';
        }

        if (Str::endsWith(strtolower($column['type']), ['int', 'integer', 'boolean', 'decimal', 'float'])) {
            $attributes['type'] = 'number';
        }

        if (in_array(strtolower($column['type']), ['date', 'datetime', 'month', 'year'])) {
            $attributes['type'] = $column['type'];
        }

        if (is_int($column['length'])) {
            $attributes['maxlength'] = $column['length'];
        }

        if ($column['unsigned']) {
            $attributes['min'] = 0;
        }

        return '<z-field ' . attribute($attributes) . '>';
    }

    /**
     * 生成控制器对应动作的模板
     *
     * @param string $action 控制器动作名称 index,create,edit,show
     * @param bool $force
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    protected function generateView(string $action, $force = false)
    {
        $this->replace([
            'list_head'    => $this->getTableColumnsView('inner.list.head.stub'),
            'list_columns' => $this->getTableColumnsView('inner.list.columns.stub'),
            'show_columns' => $this->getTableColumnsView('inner.show.columns.stub'),
            'form_columns' => $this->getTableColumnsView('inner.form.columns.stub'),
        ]);

        $stub = $this->stub . '/' . $action;
        $path = $this->getConfigDirs('views') . DIRECTORY_SEPARATOR . $this->getTypeConfig("dirs.view");
        $path = $path . DIRECTORY_SEPARATOR . $this->getLowerNameInput() . DIRECTORY_SEPARATOR . $action . '.blade.php';

        $this->generateStubFile($stub, $path, $force);
    }
}
