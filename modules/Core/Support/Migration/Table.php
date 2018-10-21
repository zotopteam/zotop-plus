<?php
namespace Modules\Core\Support\Migration;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class Table {

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
	protected $fieldTypeMap = [
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
	public function __construct($table)
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

		$this->table  = $table;
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
		return Schema::rename($this->table, $name);
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
	 * @param array $fields 字段
	 * @param array  $indexes 索引
	 * @return bool
	 */
	public function create(Array $fields, Array $indexes=[])
	{
		$fields = array_map([$this, 'convertFieldToColumn'], $fields);

		Schema::create($this->table, function (Blueprint $table) use ($fields) {
			
			foreach ($fields as $key => $field) {
				
				$column    = null;
				$name      = $field['name'];
				$type      = $field['type'];
				$arguments = $field['arguments'];
				$modifiers = $field['modifiers'];

				// 字段
				if (is_array($arguments)) {
					$column = ($type == 'enum') ? $table->$type($name, $arguments) : $table->$type($name, $arguments[0], $arguments[1]);
				} else if ($arguments) {
					$column = $table->$type($name, $arguments);
				} else {
					$column = $table->$type($name);
				}

				// 修改器
				foreach ($modifiers as $modifier => $argument) {
					if ($argument === true) {
						$column->$modifier();
					} else {
						$column->$modifier($argument);
					}
				}
			}

		});

		return true;
	}

	public function update(Array $fields, Array $indexes=[])
	{
		$columns = $this->columns();

		debug('current',$columns);
		debug('new',$fields);
	}


	/**
	 * 获取表的字段
	 * 
	 * @return array
	 */
	public function columns()
	{
		$columns = [];
		$fieldTypeMap = array_flip($this->fieldTypeMap);
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

			if (isset($fieldTypeMap[$columns[$name]['type']])) {
				$columns[$name]['type'] = $fieldTypeMap[$columns[$name]['type']];
			}

			if (in_array($columns[$name]['type'] , ['decimal', 'float', 'double'])) {
				$columns[$name]['length'] = $column->getPrecision().','.$column->getScale();
			}

			if (in_array($columns[$name]['type'] , ['tinytext', 'text', 'mediumtext','bigtext'])) {
				$columns[$name]['length'] = null;
			}
		}

		debug($columns);
		
		return $columns;
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

		debug($indexes);

		return $indexes;	
	}

	protected function convertFieldToColumn($field)
	{
		$convert = [];
		$default = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];
		
		// 合并默认值
		$field = array_merge($default, $field);

		$convert['name']       = $field['name'];
		$convert['type']       = $this->fieldTypeMap[$field['type']] ?? $field['type'];
		$convert['arguments']  = (int)$field['length'];
		$convert['modifiers'] = [];

		$convert['modifiers']['nullable'] = (bool)$field['nullable'];
		$convert['modifiers']['default']  = $field['default'] ? $field['default'] : null;
		$convert['modifiers']['comment']  = $field['comment'];

		if ($field['index'] && !$field['increments']) {
			$convert['modifiers'][$field['index']] = $field['name'];
		}

		// 数字类型，数字类型在Laravel中不能设置长度，只能按照类型长度
		if (in_array($convert['type'], ['tinyInteger', 'smallInteger', 'integer', 'bigInteger', 'mediumInteger'])) {
			
			$convert['arguments']  = [(bool)$field['increments'], (bool)$field['unsigned']];

			if (! $field['increments']) {
				$convert['modifiers']['default']  = (int)$field['default'];
			} else {
				$convert['modifiers']['nullable'] = false;
			}	
		} 		

		// 浮点类型的参数返回数组  [10,2] 或者数字 10 (精度默认2) ，允许浮点
		if (in_array($convert['type'], ['decimal', 'float', 'double'])) {
			
			list($total, $places) = explode(',', $field['length'].',2');
			
			$total  = (intval($total) >= 1 && intval($total) <= 255) ? intval($total) : 8;
			$places = (intval($places) >= 0 && intval($places) <= 30) ? intval($places) : 2;

			if ($total > $places) {
				$convert['arguments'] = [$total, $places];
			}

			$convert['modifiers']['default']  = (int)$field['default'];
			$convert['modifiers']['unsigned'] = (bool)$field['unsigned'];
		}

		// enum 类型
		if (in_array($convert['type'], ['enum'])) {
			$convert['arguments'] = explode(',', $field['length'] ?: 'Y,N');
		}	

		
		return $convert;
	}
}
