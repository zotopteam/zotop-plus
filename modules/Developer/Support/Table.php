<?php

namespace Modules\Developer\Support;

use Schema;
use Module;
use DB;

class Table
{
    /**
     * 实例化schema
     * 
     * @return Schema
     */
    protected static function schema()
    {
    	static $schema = null;

        if (! $schema) {

        	// schema manager
            $schema = Schema::getConnection()->getDoctrineSchemaManager();

            // enum and json support
            // https://github.com/laravel/framework/issues/1346
            $platform = $schema->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('enum', 'string');
            $platform->registerDoctrineTypeMapping('json', 'text');
        }

        return $schema;
    }

	/**
	 * 获取模块对应的数据表
	 * 
	 * @param  string $moduleName 模块名称
	 * @return object
	 */
	public static function module($module)
	{
		// 模块实例
		$module = Module::findOrFail($module);

		// 数据表前缀
		$prefix = DB::getTablePrefix();		

		// 全部数据表，并去掉前缀
		$tables = self::schema()->listTableNames();
		$tables = array_map(function($table) use($prefix) {
			return str_after($table, $prefix);
		}, $tables);

		// 如果module.json  中包含 tables，优先获取
		if (is_array($module->tables)) {
			$moduleTables = $module->tables;
	        $tables = array_filter($tables, function($table) use($moduleTables) {
	            return in_array($table, $moduleTables);
	        });
		} else {
			$moduleName = $module->getLowerName();
	        $tables = array_filter($tables, function($table) use($moduleName) {
	            return starts_with($table, $moduleName.'_');
	        });		
		}

		return $tables;
	}


}
