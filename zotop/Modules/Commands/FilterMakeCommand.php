<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Maker\GeneratorCommand;
use Zotop\Modules\Maker\OptionTableTrait;
use Illuminate\Support\Str;


class FilterMakeCommand extends GeneratorCommand
{
    use OptionTableTrait;

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
     * @throws \Zotop\Modules\Exceptions\FileExistedException
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
