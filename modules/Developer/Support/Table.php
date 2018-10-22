<?php
namespace Modules\Developer\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class Table
{

	/**
	 * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	protected $schema;

	/**
	 * @var string
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var array
	 */
    private $registerTypes = [
    	// Mysql types
        'enum'     => 'string',
        'json'     => 'text',
        'jsonb'    => 'text',
        'bit'      => 'boolean',
        // Postgres types
        '_text'    => 'text',
        '_int4'    => 'integer',
        '_numeric' => 'float',
        'cidr'     => 'string',
        'inet'     => 'string',
    ];

	/**
	 * Convert dbal types to Laravel Migration Types
	 * @var array
	 */
	protected $columnTypeMap = [
		'tinyint'   => 'tinyInteger',
		'smallint'  => 'smallInteger',
		'mediumint' => 'mediumInteger',
		'int'       => 'integer',
		'bigint'    => 'bigInteger',
		'varchar'	=> 'string',
		'datetime'  => 'dateTime',
		'blob'      => 'binary',
	];    

	/**
	 * @param string $database
	 * @param bool   $ignoreIndexNames
	 * @param bool   $ignoreForeignKeyNames
	 */
	public function __construct()
	{
		$prefix = DB::getTablePrefix();

        $schema = Schema::getConnection()->getDoctrineSchemaManager();

        // enum and json support
        // https://github.com/laravel/framework/issues/1346
        foreach ($this->registerTypes as $convertFrom=>$convertTo) {
            $schema->getDatabasePlatform()->registerDoctrineTypeMapping($convertFrom, $convertTo);
        }

		$this->schema = $schema;

		$this->prefix = $prefix;
	}

	/**
	 * 获取全部数据表
	 * 
	 * @return array
	 */
	public static function all($prefix=false)
	{
        $instance  = new static;

		$tables = $instance->schema->listTableNames();

		if ($prefix == false) {
			$tables = array_map(function($table) use ($instance) {
				return str_after($table, $instance->prefix);
			}, $tables);	
		}

		return $tables;        
	}

	/**
	 * 查找表
	 * @param  string $table 不含前缀
	 * @return object|false
	 */
	public static function find($table)
	{
		static $instance = null;

		if (empty($instance)) {
			$instance = new static;
			$instance->table = $table;
		}

		return $instance;
	}

	public function name($prefix=false)
	{
		return $prefix ? $this->prefix.$this->table : $this->table;
	}

	/**
	 * 检查表是否存在
	 * 
	 * @return bool
	 */
	public function exists()
	{
		return Schema::hasTable($this->table);
	}

	/**
	 * 重命名数据表
	 * 
	 * @return bool
	 */
	public function rename($name)
	{	
		Schema::rename($this->table, $name);

		if (Schema::hasTable($name)) {
			$this->table = $name;
			return true;
		}

		return false;
	}

	/**
	 * 删除表是否存在
	 * 
	 * @return bool
	 */
	public function drop()
	{
		return Schema::dropIfExists($this->table);
	}

	/**
	 * 创建表
	 * 
	 * @param array $columns 字段
	 * @param array  $indexes 索引
	 * @return bool
	 */
	public function create(Array $columns, Array $indexes=[])
	{
		$bluepoints = $this->convertColumnsIndexes($columns, $indexes);
		$bluepoints = array_merge($bluepoints['columns'], $bluepoints['indexes']);

		Schema::create($this->table, function (Blueprint $table) use ($bluepoints) {
			
			foreach ($bluepoints as $key => $bluepoint) {
				
				$method    = $bluepoint['method'];
				$arguments = $bluepoint['arguments'];
				$modifiers = $bluepoint['modifiers'] ?? [];
				
				// 执行方法
				$result = call_user_func_array([$table, $method], $arguments);

				// 修改器
				foreach ($modifiers as $modifier => $argument) {
					call_user_func_array([$result, $modifier], $argument);
				}
			}

		});

		return true;
	}

	/**
	 * 获取表的字段
	 * 
	 * @return array
	 */
	public function columns()
	{
		$columns = [];
		$columnTypeMap = array_flip($this->columnTypeMap);
		$column_list = $this->schema->listTableColumns($this->prefix.$this->table);

		foreach ($column_list as $name=>$column) {
			
			$columns[$name]['name']       = $column->getName();
			$columns[$name]['type']       = $column->getType()->getName();
			$columns[$name]['length']     = $column->getLength();
			$columns[$name]['default']    = $column->getDefault();
			$columns[$name]['nullable']   = $column->getNotNull() ? '' : 'nullable';
			$columns[$name]['unsigned']   = $column->getUnsigned() ? 'unsigned' : '';
			$columns[$name]['increments'] = $column->getAutoincrement() ? 'increments' : '';
			$columns[$name]['index']      = '';
			$columns[$name]['comment']    = $column->getComment();

			if ($columns[$name]['type'] == 'string' && $column->getFixed()) {
				$columns[$name]['type'] = 'char';
			}

			if (isset($columnTypeMap[$columns[$name]['type']])) {
				$columns[$name]['type'] = $columnTypeMap[$columns[$name]['type']];
			}

			if (in_array($columns[$name]['type'] , ['decimal', 'float', 'double'])) {
				$columns[$name]['length'] = $column->getPrecision().','.$column->getScale();
			}

			if (in_array($columns[$name]['type'] , ['tinytext', 'text', 'mediumtext','bigtext'])) {
				$columns[$name]['length'] = null;
			}
		}

		//debug($columns);
		
		return $columns;
	}

	public function dropColumn($column)
	{
		Schema::table($this->table, function (Blueprint $table) use ($column) {
		    $table->dropColumn($column);
		});		
	}

	/**
	 * 获取表的索引
	 * 
	 * @return array
	 */
	public function indexes()
	{
		$indexes = [];
		$index_list = $this->schema->listTableIndexes($this->prefix.$this->table);

		foreach ($index_list as $key => $index) {

			$indexes[$key]['name']    = $index->getName();
			$indexes[$key]['columns'] = $index->getColumns();
			$indexes[$key]['type']    = 'index';

			if ( $index->isUnique() ) {
				$indexes[$key]['type'] = 'unique';
			}

			if ( $index->isPrimary() ) {
				$indexes[$key]['type'] = 'primary';
			}		
		}

		//debug($indexes);

		return $indexes;	
	}

	public function convertColumnsIndexes($columns, $indexes)
	{
		$columns = $this->formatColumns($columns);

		foreach ($indexes as $key=>$index) {
			if (count($index['columns']) == 1) {
				$column = $index['columns'][0];
				if (isset($columns[$column])) {
					$columns[$column]['index'] = $index['type'];
				}
				unset($indexes[$key]);
			} 
		}

		$columns = array_map([$this, 'convertColumn'], $columns);
		$indexes = array_map([$this, 'convertIndex'], $indexes);

		return ['columns'=>array_values($columns), 'indexes'=>array_values($indexes)];
	}

	/**
	 * 格式化字段为标准格式
	 * 
	 * @param  array $columns 字段
	 * @return array
	 */
	public function formatColumns($columns)
	{
		$format = [];
		$default = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];

		foreach ($columns as $column) {
			if (isset($column['name']) && $column['name']) {
				$format[$column['name']] = array_merge($default, $column);
			}
		}

		return $format;
	}

	/**
	 * 格式化索引为标准格式
	 * 
	 * @param  array $columns 字段
	 * @return array
	 */
	public function formatIndexes($indexes)
	{
		$format = [];
		foreach ($indexes as $index) {
			if (in_array($index['type'], ['primary', 'index', 'unique']) && $index['columns']) {

				$index['columns'] = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
				$name = implode('_', array_sort($index['columns']));

				$format[$name]['type']    =  $index['type'];
				$format[$name]['columns'] =  $index['columns'];
				$format[$name]['name']    =  $name;
			}
		}

		return $format;
	}


	/**
	 * 转化字段为 Bluepoint 友好格式
	 * @param  array $column 字段
	 * @return array
	 */
	public function convertColumn($column)
	{
		$convert = [];

		$convert['method']     = $this->columnTypeMap[$column['type']] ?? $column['type'];

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

		if ($column['comment']) {
			$convert['modifiers']['comment'] = [$column['comment']];
		}

		if ($column['index'] && !$column['increments']) {
			$convert['modifiers'][$column['index']] = [$column['name']];
		}

		// 数字类型，数字类型在Laravel中不能设置长度，只能按照类型长度
		if (in_array($convert['method'], ['tinyInteger', 'smallInteger', 'integer', 'bigInteger', 'mediumInteger'])) {
			
			if ($column['increments']) {
				$convert['method'] = 'increments';
			} else {
				// Laravel 的数字函数没有长度参数
				//$convert['arguments']['length']  = [intval($column['length'])];
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

		// enum 类型 暂不支持
		// if (in_array($convert['type'], ['enum'])) {
		// 	$convert['arguments'] = explode(',', $column['length'] ?: 'Y,N');
		// }
		
		return $convert;
	}

	public function convertIndex($index)
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

	public function __toString()
	{
		return $this->table;
	}
}
