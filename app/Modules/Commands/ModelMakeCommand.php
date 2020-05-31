<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Modules\Maker\GeneratorCommand;


class ModelMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                {module : The module to use}
                {name : The name to use}
                {--table= : The table name to use.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module.';


    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'model';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'model';


    /**
     * 生成前准备
     * @return boolean
     */
    public function prepare()
    {
        $this->replace([
            'table'      => $this->getTableName(),
            'fillable'   => $this->getFillable(),
            'guarded'    => $this->getGuarded(),
            'timestamps' => $this->getTimestamps(),
        ]);

        return true;
    }

    /**
     * 获取数据表名称
     * @return string
     */
    public function getTableName()
    {
        // 如果有table，则直接使用table, 否则使用name的复数
        if ($table = $this->option('table')) {
            return strtolower($table);
        }

        $table = $this->getLowerNameInput();
        $table = Str::plural($table);

        return $table;
    }

    /**
     * 获取数据表全部字段
     * @return array
     */
    public function getTableColums()
    {
        static $columns = [];

        if (empty($columns)) {
            $table = $this->getTableName();

            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
            }
        }

        return $columns;
    }

    /**
     * 获取数据表可填充字段
     * @return string
     */
    public function getFillable()
    {
        // 从数据表中直接读取字段
        if ($columns = $this->getTableColums()) {
            return "['" . implode("','", $columns) . "']";
        }

        return '[]';
    }

    /**
     * 不可批量赋值的属性，默认为全部可赋值
     * @return string
     */
    public function getGuarded()
    {
        return '[]';
    }

    /**
     * 时间戳，如果含有created_at 和 updated_at，则真，否则假
     * @return string
     */
    public function getTimestamps()
    {
        $columns = $this->getTableColums();

        if (in_array('created_at', $columns) && in_array('updated_at', $columns)) {
            return 'true';
        }

        return 'false';
    }
}
