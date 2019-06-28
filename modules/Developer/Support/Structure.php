<?php

namespace Modules\Developer\Support;

class Structure
{
	/**
	 * 字段
	 * @var collection
	 */
	protected $columns;

	/**
	 * 索引
	 * @var collection
	 */
	protected $indexes;

	/**
	 * 自增
	 * @var collection
	 */
	protected $increments;

	/**
	 * 字段默认格式
	 * 
	 * @var collection
	 */
	protected static $columnFormatDefault = [
		'name'       => '',
		'type'       => 'varchar',
		'length'     => '',
		'nullable'   => '',
		'unsigned'   => '',
		'increments' => '',
		'index'      => '',
		'default'    => '',
		'comment'    => '',
		'after'      => '',
	];

	/**
	 * 数据库字段类型 对应的 laravel 迁移函数
	 * @var array
	 */
	protected static $columnTypeMap = [
		'tinyint'    => 'tinyInteger',
		'smallint'   => 'smallInteger',
		'mediumint'  => 'mediumInteger',
		'int'        => 'integer',
		'bigint'     => 'bigInteger',
	    'mediumtext' => 'mediumText',
	    'longtext'   => 'longText',
		'varchar'    => 'string',
		'datetime'   => 'dateTime',
		'blob'       => 'binary',
	];

	/**
	 * 自增类型转换
	 * @var array
	 */
	protected static $incrementsMap = [
		'tinyInteger'   => 'tinyIncrements',
		'smallInteger'  => 'smallIncrements',
		'mediumInteger' => 'mediumIncrements',
		'bigInteger'    => 'bigIncrements',
		'integer'       => 'increments',		
	];

	/**
	 * 初始化
	 * @param array $columns 字段数组
	 * @param array $indexes 索引数组
	 */
	public function __construct(array $columns = [], array $indexes = [])
	{
		// 校验并格式化字段
		$this->columns = collect($columns)->filter(function($column) {
			return $this->validColumn($column);
		})->map(function($column){
			return $this->formatColumn($column);
		});

		// 校验并格式化索引
		$this->indexes = collect($indexes)->filter(function($index) {
			return $this->validIndex($index);
		})->map(function($index) {
			return $this->formatIndex($index);
		});
	}

	/**
	 * 获取实例
	 * 
	 * @param array $columns 字段数组
	 * @param array $indexes 索引数组
	 * @return this
	 */
	public static function instance(array $columns=[], array $indexes =[])
	{
		return new static($columns, $indexes);
	}

	/**
	 * 获取columns集合
	 * @return collection
	 */
	public function columns()
	{
		return $this->columns;
	}

	/**
	 * 获取indexes集合
	 * @return collection
	 */
	public function indexes()
	{
		return $this->indexes;
	}

	/**
	 * 获取自增字段名称
	 * @return string
	 */
	public function increments()
	{
		return $this->columns->where('increments', 'increments')->first()['name'];
	}

	/**
	 * 验证column是否有效
	 * @param  mixed $column
	 * @return bool
	 */
	public function validColumn($column)
	{	
		// 包含 name 和 type 不为空的
		if (is_array($column)) {
			$column = array_where($column, function($item) {
				return !empty($item);
			});
			return array_has($column, ['name','type']);
		}

		return false;
	}

	/**
	 * 是否已经存在字段
	 * @param  mixed $column 字段数组或者字段名称
	 * @return bool
	 */
	public function existsColumn($column)
	{
		$name = is_array($column) ? ($column['name'] ?? null) : $column;

		if ($name && $this->columns->where('name', $name)->count() > 0 ) {
			return true;
		}

		return false;
	}	

	/**
	 * 验证index是否有效
	 * @param  mixed $index
	 * @return bool
	 */
	public function validIndex($index)
	{
		if (is_array($index)) {

			// 去掉index中的空值
			$index = array_where($index, function($item) {
				return !empty($item);
			});

			// 索引结构正确
			if (array_has($index, ['type','columns']) && in_array($index['type'], ['primary', 'index', 'unique'])) {

		        // 如果有自增，则主键索引无效
		        // if ($this->increments() && $index['type'] == 'primary') {
		        //     return false;
		        // }

				// 格式化索引字段
				$columns = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
				
				// 如果columns字段中存在对应的索引字段，索引有效
				$names = $this->columns->pluck('name')->all();

				if (! array_diff($columns, $names)) {
					return true;
				}			
			}
		}

		return false;
	}

	/**
	 * 是否已经存在索引，
	 * 
	 * @param  mixed $index 索引数组（已经检查过为有效索引）
	 * @return bool
	 */
	public function existsIndex($index)
	{	
        // 如果有自增或者已经有主键，则不能添加主键索引
        if ($index['type'] == 'primary' && ($this->increments() || $this->indexes->where('type','primary')->count()>0)) {
            return true;
        }

        $columns = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
        
        // 判断索引字段是否一致
        $exists = $this->indexes->filter(function($item) use ($columns) {
        	return count($item['columns']) == count($columns) && !array_diff($item['columns'], $columns);
        });

        //debug($exists->all());

        if ($exists->count()) {
        	return true;
        }
        
		return false;
	}	

	/**
	 * 格式化column
	 * @param  array $column
	 * @return array
	 */
	public static function formatColumn(array $column)
	{
		$column = array_merge(static::$columnFormatDefault, $column);

		// 转化创建时间、更新时间和删除时间为 timestamp 类型
		if (in_array($column['name'], ['created_at','updated_at', 'deleted_at'])) {
			$column['type'] = 'timestamp';
		}

		// 去掉不允许长度的 length 属性
		if (! in_array($column['type'], ['char', 'varchar', 'float', 'double','decimal','enum'])) {
			$column['length'] = null;
		}

		if ($column['increments']) {
			$column['default'] = null;
		}

		return $column;
	}

	/**
	 * 格式化index
	 * @param  array $index
	 * @return array
	 */
	public static function formatIndex(array $index)
	{
		$index['columns'] = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
		$index['columns'] = array_sort(array_unique($index['columns']));

		if (!isset($index['name']) || empty($index['name'])) {
			if ($index['type'] == 'primary') {
				$index['name'] = 'PRIMARY';
			} else {
				$index['name'] = implode('_', $index['columns']);
			}
		}


		return $index;
	}	

	/**
	 * 添加新字段
	 * @return this 
	 */
	public function addColumn($column)
	{
		if ($this->validColumn($column)) {

			if ($this->existsColumn($column)) {
				abort(403, trans('developer::table.column.exists'));
			}

			$column = $this->formatColumn($column);
			$this->columns->push($column);
			return true;
		}

		return false;
	}

	/**
	 * 添加空白行
	 */
	public function addBlank()
	{
		$this->columns->push(static::$columnFormatDefault);
		return true;
	}

	/**
	 * 添加时间戳
	 */
	public function addTimestamps()
	{
		// 检查 created_at 和 updated_at 是否已经存在
		if ($this->existsColumn('created_at') && $this->existsColumn('updated_at')) {
		    abort(403, trans('developer::table.column.exists'));
		}

        $this->addColumn(['name'=>'created_at','type'=>'timestamp','nullable'=>'nullable']);
        $this->addColumn(['name'=>'updated_at','type'=>'timestamp','nullable'=>'nullable']);

        return true;
	}

	/**
	 * 添加软删除
	 */
	public function addSoftdeletes()
	{
        $this->addColumn(['name'=>'deleted_at','type'=>'timestamp','nullable'=>'nullable']);
        return true;
	}

	/**
	 * 删除字段
	 * @param  mixed $column 字段数组或者字段名称
	 * @return bool
	 */
	public function dropColumn($column)
	{
		$name = is_array($column) ? ($column['name'] ?? null) : $column;

		$this->columns->filter(function($column) use($name) {
			return $column['name'] != $name;
		});

		return true;
	}

	/**
	 * 添加索引
	 * @param array $index
	 * @return bool
	 */
	public function addIndex($index)
	{
		if ($this->validIndex($index)) {
			
			if ($this->existsIndex($index)) {
				abort(403, trans('developer::table.index.exists'));
			}

			$index = $this->formatIndex($index);
			$this->indexes->push($index);
			return true;			
		}

		return false;
	}

	/**
	 * 将类型type转化为laravel支持的函数名称
	 * 
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public static function convertTypeToMethod($type)
	{
		return static::$columnTypeMap[$type] ?? $type;
	}

	/**
	 * 将类型type转化为数据库（mysql）支持的名称
	 * 
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public static function convertTypeToDatabase($type)
	{
		static $databaseTypeMap = null;

		if (empty($databaseTypeMap)) {
			$databaseTypeMap = array_flip(static::$columnTypeMap);
		}

		return $databaseTypeMap[$type] ?? $type;
	}

	/**
	 * 转化字段为 laravel 友好格式
	 * 
	 * @param  array $column 字段
	 * @param  bool $change 是否追加change为modifier
	 * @return array
	 */
	public static function convertColumnToLaravel($column, $change = false)
	{
		$convert = [];

		// 可用方法
		$convert['method']     = static::convertTypeToMethod($column['type']);

		// 可用的字段类型方法的第一个参数总是字段名称
		$convert['arguments'] = [$column['name']];
		
		// 存储修改器，方法名称=>参数数组
		$convert['modifiers'] = [];

		if ($column['nullable']) {
			$convert['modifiers']['nullable'] = [];
		}

		if ($column['default']) {
			$convert['modifiers']['default'] = [$column['default']];
		}

		//如果没有字段说明，设为字段名称
		if ($column['comment']) {
			$convert['modifiers']['comment'] = [$column['comment']];
		} else {
			$convert['modifiers']['comment'] = [ucfirst($column['name'])];
		}

		//  自增字段或者 'text','mediumText','longText' 类型字段不使用索引
		if ($column['index'] && !$column['increments'] && !in_array($convert['method'], ['text','mediumText','longText'])) {
			$convert['modifiers'][$column['index']] = [$column['name']];
		}

		// 数字类型，数字类型在Laravel中不能设置长度，只能按照类型长度
		if (in_array($convert['method'], ['boolean', 'tinyInteger', 'smallInteger', 'integer', 'bigInteger', 'mediumInteger'])) {
			
			if ($column['increments']) {
				$convert['method'] = static::$incrementsMap[$convert['method']];
				
				if ($change) {
					$convert['modifiers']['default']  = [null];
				}
			} else {
				$convert['modifiers']['default']  = [intval($column['default'])];

				if ($column['unsigned']) {
					$convert['modifiers']['unsigned'] = [];
				}
			}
		} 		

		// 浮点类型的参数返回数组  [10,2] 或者数字 10 (精度默认2) ，允许浮点
		if (in_array($convert['method'], ['decimal', 'float', 'double']) && $column['length']) {
			
			list($total, $places) = explode(',', $column['length'].',2');
			
			$total  = (intval($total) >= 1 && intval($total) <= 255) ? intval($total) : 8;
			$places = (intval($places) >= 0 && intval($places) <= 30) ? intval($places) : 2;

			// decimal, float, double 最多两个参数（长度、精度）
			if ($total > $places) {
				$convert['arguments'][]  = $total;
				$convert['arguments'][] = $places;
			}

			$convert['modifiers']['default']  = [floatval($column['default'])];

			if ($column['unsigned']) {
				$convert['modifiers']['unsigned'] = [];
			}
		}

		// 字符串类型如果设置了长度，加入长度参数
		if (in_array($convert['method'], ['string', 'char']) && intval($column['length'])) {
			$convert['arguments'][]  = intval($column['length']);
		}

		// enum 类型
		if (in_array($convert['method'], ['enum'])) {
			$convert['arguments'][] = explode(',', $column['length'] ?: 'Y,N');
		}

		// position
		if (isset($column['after']) && $column['after']) {
			if ($column['after'] == '__FIRST__') {
				$convert['modifiers']['first'] = [];
			} else {
				$convert['modifiers']['after'] = [$column['after']];
			}
		}

		// 如果是修改数据表
		if ($change) {

			// 修改为非空字段
			if(! $column['nullable']) {
				$convert['modifiers']['nullable'] = [false];
			}

			$convert['modifiers']['change'] = [];
		}
		
		return $convert;
	}

	/**
	 * 转化索引为 laravel 友好格式
	 * 
	 * @param  array $column 字段
	 * @return array
	 */	
	public static function convertIndexToLaravel($index)
	{
		$convert = [];

		// 方法名称：primary, index, unique
		$convert['method']   = $index['type'];

		// 方法的参数：primary, index, unique 函数的最多允许两个参数
		// 单一索引第一个参数为字段名称，符合索引第一个参数为字段名称数组
		$convert['arguments'] = [];
		$convert['arguments'][] = (count($index['columns']) == 1) ? reset($index['columns']) : $index['columns'];

		// 第二个参数为索引名称 primary 类型不能设置名称
		if ($convert['method'] != 'primary') {
			$convert['arguments'][] = $index['name'];
		}

		return $convert;	
	}

	/**
	 * 将Structure转化为 laravel 友好格式
	 * 
	 * @return [type] [description]
	 */
	public function convertToLaravel()
	{
		$convert = [];

		// 获取非单一索引并转化
		$indexes = $this->indexes->filter(function($index) {

			// 获取全部的单一索引字段直接附加在字段上，最终转化时，直接作为 modifier 执行
			if ($single = (count($index['columns']) == 1)) {
				$this->columns->transform(function($column) use ($index) {
					if ($column['name'] == $index['columns'][0]) {
						$column['index'] = $index['type'];
					}
					return $column;
				});
			}

			return ! $single;
		})->map(function($index) {
			return static::convertIndexToLaravel($index);
		})->values();

		// 转化字段
		$columns = $this->columns->map(function($column) {
			return static::convertColumnToLaravel($column);
		})->values();

		// 合并集合
		$result = $columns->merge($indexes);

		return $result;
	}
}
