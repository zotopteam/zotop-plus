<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use App\Modules\Maker\OptionTableTrait;
use App\Modules\Maker\OptionTypeTrait;
use App\Modules\Maker\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RequestMakeCommand extends GeneratorCommand
{
    use OptionTableTrait, OptionTypeTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request
                {module : The module to use}
                {name? : The name to use}
                {--table= : The table name to use}
                {--type=frontend : The type of request, [backend|frontend|api].}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = 'Request';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var null
     */
    protected $dirKey = 'request';


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
     * 生成前准备
     *
     * @return boolean
     */
    public function prepare()
    {
        if ($this->getTableName()) {
            $this->prepareTable();
        } else {
            $this->preparePlain();
        }

        return true;
    }

    /**
     * 生成简单的验证
     *
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function preparePlain()
    {
        $this->stub = 'request/plain.stub';

        $this->replace([
            'lang_name'    => $this->getLowerNameInput(),
            'parent_class' => $this->getParentClass(),
        ]);
    }


    /**
     * 生成特定表的验证
     *
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function prepareTable()
    {
        $this->stub = 'request/table.stub';

        $this->replace([
            'lang_name'    => $this->getLowerNameInput(),
            'parent_class' => $this->getParentClass(),
            'rules'        => $this->getRules(),
            'attributes'   => $this->getAttributes(),
            'messages'     => $this->getMessages(),
        ]);
    }

    /**
     * 获取父类，如果使用了api中间件，则使用ApiRequest
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getParentClass()
    {
        $middlewares = $this->getTypeConfig('middleware');

        if (in_array('api', $middlewares)) {
            return 'ApiRequest';
        }

        return 'FormRequest';
    }

    /**
     * 获取表的字段信息
     *
     * @return \Illuminate\Support\Collection
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getTableColumns()
    {
        $table = $this->getTableName();

        if (Schema::hasTable($table)) {
            return Table::find($table)->columns()->reject(function ($column) {
                return $column['increments'] == 1 || in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
            });
        }

        return collect([]);

    }

    /**
     * 获取规则字符串
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getRules()
    {
        $columns = $this->getTableColumns();

        if ($columns->isEmpty()) {
            return '';
        }

        // 获取键名最大长度
        $maxlength = $columns->keys()->map(function ($key) {
            return strlen($key);
        })->max();

        return $columns->transform(function ($column) use ($maxlength) {
            $name = $column['name'];
            return "            " . str_pad("'" . $name . "'", $maxlength + 2, "  ") . " => '" . $this->getColumnRule($column) . "',";
        })->implode(PHP_EOL);
    }

    /**
     * 获取每行的规则
     *
     * @param array $column
     * @return string
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getColumnRule(array $column)
    {
        $rules = [];

        if ($column['nullable'] == 0) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        if (Str::endsWith(strtolower($column['type']), ['int', 'integer', 'boolean'])) {
            $rules[] = "integer";
        }

        if (Str::endsWith(strtolower($column['type']), ['string', 'char', 'text'])) {
            $rules[] = "string";
        }

        if (in_array(strtolower($column['type']), ['decimal', 'float'])) {
            $rules[] = "numeric";
        }

        if (in_array(strtolower($column['type']), ['date', 'datetime', 'timestamp'])) {
            $rules[] = "date";
        }

        if (is_int($column['length'])) {
            $rules[] = "max:{$column['length']}";
        }

        if ($column['unsigned']) {
            $rules[] = "min:0";
        }

        return implode('|', $rules);
    }

    /**
     * 获取标签
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getAttributes()
    {
        $columns = $this->getTableColumns();

        if ($columns->isEmpty()) {
            return '';
        }

        // 获取键名最大长度
        $maxlength = $columns->keys()->map(function ($key) {
            return strlen($key);
        })->max();

        return $columns->transform(function ($column) use ($maxlength) {
            $name = $column['name'];
            $module = $this->getModuleLowerName();
            $filename = $this->getLowerNameInput();
            return "            " . str_pad("'" . $name . "'", $maxlength + 2, "  ") . " => trans('" . $module . "::" . $filename . "." . $name . ".label'),";
        })->implode(PHP_EOL);
    }

    /**
     * 获取自定义错误消息
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function getMessages()
    {
        $columns = $this->getTableColumns();

        if ($columns->isEmpty()) {
            return '';
        }

        // 获取键名最大长度
        $maxlength = $columns->keys()->map(function ($key) {
            return strlen($key);
        })->max();

        return $columns->transform(function ($column) use ($maxlength) {
            $name = $column['name'];
            $module = $this->getModuleLowerName();
            $filename = $this->getLowerNameInput();
            return "            " . str_pad("'" . $name . "'", $maxlength + 2, "  ") . " => trans('" . $module . "::" . $filename . "." . $name . ".help'),";
        })->implode(PHP_EOL);
    }
}
