<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use App\Modules\Maker\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class FilterMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-filter
                {module : The module to use}
                {name? : The name of the class}
                {--table= : The table name of the model}
                {--force : Create the class even if the model already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model query filter for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = 'Filter';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var string
     */
    protected $dirKey = 'filter';

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
        if (empty($this->argument('name')) && empty($this->option('table'))) {
            $this->error('The name or --table required');
            return false;
        }

        parent::handle();
    }

    /**
     * 生成前准备
     *
     * @return bool
     * @throws \Exception
     * @author Chen Lei
     * @date 2020-11-19
     */
    protected function prepare()
    {
        $this->stub = 'model/filter.stub';

        $this->replace([
            'filters' => $this->getFilters(),
        ]);

        return true;
    }

    /**
     * 获取输入的 name
     *
     * @return string|bool
     */
    protected function getNameInput()
    {
        // name 为可选值，如果没有输入name，则必须输入table
        if (!empty($this->argument('name'))) {
            return trim($this->argument('name'));
        }

        // 使用--table值作为name时，去掉前面的模块名称
        $name = Str::after($this->getTableName(), $this->getModuleLowerName() . '_');

        return $name;
    }


    /**
     * 获取数据表名称
     *
     * @return string
     */
    protected function getTableName()
    {
        // 如果有table，则直接使用table值
        if ($table = $this->option('table')) {
            return strtolower(trim($table));
        }

        // 没有传入table，使用name的小写的复数格式作为表名称
        $table = $this->getLowerNameInput();
        $table = Str::plural($table);

        return $table;
    }

    /**
     * 获取字段列表
     *
     * @return \Illuminate\Support\Collection
     * @author Chen Lei
     * @date 2020-11-24
     */
    protected function getTableColumns()
    {

        $table = $this->getTableName();

        if (Schema::hasTable($table)) {
            return $columns = Table::find($table)->columns();
        }

        return collect([]);
    }

    /**
     * 获取数据表可填充字段
     *
     * @return string
     */
    protected function getFilters()
    {
        $columns = $this->getTableColumns();

        $template = $this->getStubContent('model/filter-method.stub');

        // 从数据表中直接读取字段
        if ($columns->isNotEmpty()) {
            return $columns->transform(function ($column) {
                return [
                    'column'  => $column['name'],
                    'name'    => Str::camel($column['name']),
                    'comment' => $column['comment'] ?: $column['name'],
                    'type'    => $this->convertToPhpType($column['type']),
                    'method'  => $this->convertToMethod($column['type']),
                ];
            })->transform(function ($column) use ($template) {
                foreach ($column as $key => $value) {
                    $template = str_replace('$' . strtoupper($key) . '$', $value, $template);
                }
                return $template;
            })->implode('');
        }

        return '';
    }

    /**
     * 转换字段类型为php类型
     *
     * @param string $type
     * @return string
     * @author Chen Lei
     * @date 2020-11-24
     */
    private function convertToPhpType(string $type)
    {
        $type = strtolower($type);

        if (Str::endsWith($type, 'int') || Str::endsWith($type, 'integer')) {
            return 'int';
        }

        return 'string';
    }

    /**
     * 转换字段类型为方法类型
     *
     * @param string $type
     * @return string
     * @author Chen Lei
     * @date 2020-11-24
     */
    private function convertToMethod(string $type)
    {
        $type = strtolower($type);

        if (Str::endsWith($type, 'text')) {
            return 'searchIn';
        }

        return 'where';
    }
}
