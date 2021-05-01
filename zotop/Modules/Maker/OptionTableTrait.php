<?php

namespace Zotop\Modules\Maker;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait OptionTableTrait
{
    /**
     * 获取输入的 name 或者是 去掉模块名称后的表名称
     *
     * @return string
     */
    protected function getNameInput()
    {
        return parent::getNameInput() ?: $this->getTableShortName();
    }

    /**
     * 获取数据表名称
     *
     * @return string
     */
    protected function getTableName()
    {
        $table = strtolower(trim($this->option('table')));

        if ($table && !Schema::hasTable($table)) {
            $this->inputError("Table {$table} does not exist!");
        }

        return $table;
    }

    /**
     * 获取表不含模块前缀的短名称，
     *
     * @return string
     * @author Chen Lei
     * @date 2021-01-15
     */
    protected function getTableShortName()
    {
        return Str::after($this->getTableName(), $this->getModuleLowerName() . '_');
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

        return Table::find($table)->columns();
    }

    /**
     * name 或者 --table 必须存在其一
     *
     * @throws \Zotop\Modules\Exceptions\InputException
     * @author Chen Lei
     * @date 2021-01-15
     */
    protected function requireTableOrName()
    {
        if (!empty(trim($this->argument('name'))) || !empty(trim($this->option('table')))) {
            return;
        }

        $this->inputError("The name or --table required!");
    }
}
