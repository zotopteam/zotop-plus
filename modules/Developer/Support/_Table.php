<?php

namespace Modules\Developer\Support;

use Schema;
use Module;
use DB;


class Table
{
    private static $registerTypes = [
        'enum' => 'string',
        'josn' => 'text',
        'bit'  => 'boolean'
    ];

    private static $fieldTypeMap = [
        'guid'       => 'string',
        'bigint'     => 'integer',
        'littleint'  => 'integer',
        'datetimetz' => 'datetime'
    ];

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
            foreach (static::$registerTypes as $convertFrom=>$convertTo) {
                $schema->getDatabasePlatform()->registerDoctrineTypeMapping($convertFrom, $convertTo);
            }
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
	            return $table == $moduleName || starts_with($table, $moduleName.'_');
	        });	
		}

		return $tables;
	}

    // public static function migration($module, $table)
    // {
    //     // 获取前缀 cms_
    //     $prefix = DB::getTablePrefix();

    //     // 组装生成文件名称
    //     $table = strtolower($table);
    //     $name = 'create_'.$table.'_table';

    //     //获取表的字段
    //     $fields = static::detectColumns($prefix.$table);
    //     var_dump($fields);
    //     exit($name);
    // }

    // public static function detectColumns($table)
    // {
    //     $schema = self::schema();

    //     $unique = [];
    //     $indexes = $schema->listTableIndexes($table);
    //     foreach ($indexes as $index) {
    //         if($index->isUnique()) {
    //             $unique[$index->getName()] = true;
    //         }
    //     }

    //     $fields = [];
    //     $columns = $schema->listTableColumns($table);

    //     if ($columns) {
    //         foreach ($columns as $column) {
    //             $name     = $column->getName();
    //             $type     =  $column->getType()->getName();
    //             $length   = $column->getLength();
    //             $default  = $column->getDefault();
    //             $nullable = $column->getNotNull() ? false :true;

    //             // 类型转换
    //             if (isset(static::$fieldTypeMap[$type])) {
    //                 $type = static::$fieldTypeMap[$type];
    //             }
                
    //             // 字段拼接
    //             $field = "$name:$type";

    //             if($length){
    //                $field .= "($length)";
    //             }

    //             if($nullable){
    //                 $field .= ':nullable';
    //             }

    //             if(isset($unique[$name])){
    //                 $field .= ':unique';
    //             }

    //             $fields[] = $field;
                
    //         }
    //     }

    //     return implode(', ', $fields);
    // }
}
