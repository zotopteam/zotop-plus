<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Maker\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class ModelMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                {module : The module to use}
                {name? : The name of the class}
                {--table= : The table name of the model}
                {--force : Create the class even if the model already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module.';


    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var string
     */
    protected $dirKey = 'model';

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
        $this->stub = 'model/plain.stub';

        // 如果含有软删除，则创建软删除模型
        if ($this->hasSoftDeletes()) {
            $this->stub = 'model/soft-deletes.stub';
        }

        $this->replace([
            'table'      => $this->getTableName(),
            'fillable'   => $this->getFillable(),
            'guarded'    => $this->getGuarded(),
            'timestamps' => $this->getTimestamps(),
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
     * 获取数据表全部字段
     *
     * @return array
     */
    protected function getTableColumns()
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
     *
     * @return string
     */
    protected function getFillable()
    {
        // 从数据表中直接读取字段
        if ($columns = $this->getTableColumns()) {
            return "['" . implode("', '", $columns) . "']";
        }

        return '[]';
    }

    /**
     * 不可批量赋值的属性，默认为全部可赋值
     *
     * @return string
     */
    protected function getGuarded()
    {
        return '[]';
    }

    /**
     * 时间戳，如果含有created_at 和 updated_at，则真，否则假
     *
     * @return string
     */
    protected function getTimestamps()
    {
        $columns = $this->getTableColumns();

        if (in_array('created_at', $columns) && in_array('updated_at', $columns)) {
            return 'true';
        }

        return 'false';
    }

    /**
     * 是否含有软删除
     *
     * @return bool
     * @author Chen Lei
     * @date 2020-11-19
     */
    protected function hasSoftDeletes()
    {
        $columns = $this->getTableColumns();

        if (in_array('deleted_at', $columns)) {
            return true;
        }

        return false;
    }
}
