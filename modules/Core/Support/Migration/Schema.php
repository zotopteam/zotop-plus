<?php
namespace Modules\Core\Support\Migration;

use Illuminate\Support\Facades\DB;

class Schema {

	/**
	 * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	protected $schema;

	/**
	 * @var FieldGenerator
	 */
	protected $field;

	/**
	 * @var ForeignKeyGenerator
	 */
	protected $foreignKey;

	/**
	 * @var string
	 */
	protected $database;

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
	 * @param string $database
	 * @param bool   $ignoreIndexNames
	 * @param bool   $ignoreForeignKeyNames
	 */
	public function __construct()
	{
		$database   = config('database.default');
		$prefix     = DB::getTablePrefix();
		$connection = DB::connection($database)->getDoctrineConnection();

        // enum and json support
        // https://github.com/laravel/framework/issues/1346
        foreach ($this->registerTypes as $convertFrom=>$convertTo) {
            $connection->getDatabasePlatform()->registerDoctrineTypeMapping($convertFrom, $convertTo);
        }		

		$this->database = $connection->getDatabase();

		$this->prefix   = $prefix;

		$this->schema   = $connection->getSchemaManager();

		$this->field    = new Field($this->schema, $this->database);

		$this->foreignKey = new ForeignKey($this->schema);
	}

	/**
	 * 获取全部的数据表
	 * 
	 * @return mixed
	 */
	public function getTables($prefix=false)
	{
		$tables = $this->schema->listTableNames();

		if ($prefix == false) {
			$tables = array_map(function($table) {
				return str_after($table, $this->prefix);
			}, $tables);		
		}

		return $tables;
	}

	/**
	 * 获取表的字段
	 * 
	 * @param  string $table 表名，不含前缀
	 * @return array
	 */
	public function getFields($table)
	{
		$table = $this->prefix.$table;

		return $this->field->get($table);
	}

	/**
	 * 获取表的字段
	 * 
	 * @param  string $table 表名，不含前缀
	 * @return array
	 */
	public function getForeignKeys($table)
	{
		$table = $this->prefix.$table;

		return $this->foreignKey->get($table);
	}

	/**
	 * 获取创建表的代码
	 * 
	 * @param  string $table  表名称，不含前缀
	 * @param  string $template 模板
	 * @return string
	 */
	public function getCreateTable($table, $template=null)
	{
		$template = $template ? $template : base_path('modules/Core/Support/Migration/stubs/create_table.stub');

		$name   = $this->getFileName($table, 'create');
		$fields = $this->getFieldsUp($table);

		$data = [
			'CLASS'  => ucwords(camel_case($name)),
			'TABLE'  => $table,
			'FIELDS' => $fields,
		];

		return $this->compile($template, $data);
	}

	/**
	 * 获取文件名称
	 * 
	 * @param  string $table  表名称，不含前缀
	 * @param  string $type 类型
	 * @return string
	 */
	public function getFileName($table, $type)
	{
		$filename = 'create_'. $table .'_table';

		return $filename;
	}

	/**
	 * 获取字段创建语句
	 * 
	 * @param  string $table  表名称，不含前缀
	 * @return string
	 */
	protected function getFieldsUp($table)
	{
		$fields = $this->getFields($table);

		$return = [];

		foreach ($fields as $field) {
			$name = $field['field'];
			$type = $field['type'];

			// If the field is an array,
			// make it an array in the Migration
			if (is_array($name)) {
				$name = "['". implode("','", $name) ."']";
			} else {
				$name = $name ? "'$name'" : null;
			}

			$output = sprintf("\$table->%s(%s)", $type, $name);			

			if (isset($field['args'])) {
				$output = sprintf("\$table->%s(%s, %s)", $type, $name, $field['args']);
			}

			if (isset($field['decorators'])) {
				$output .= $this->addDecorators($field['decorators']);
			}

			$output .= ';';

			$return[] = $output;
		}

		$return = implode(PHP_EOL."\t\t\t", $return);

		return $return;
	}

	/**
	 * @param $decorators
	 * @return string
	 */
	protected function addDecorators($decorators)
	{
		$output = '';

		foreach ($decorators as $decorator) {
			$output .= sprintf("->%s", $decorator);
			// Do we need to tack on the parentheses?
			if (strpos($decorator, '(') === false) {
				$output .= '()';
			}
		}

		return $output;
	}	

	/**
     * Compile the template using
     * the given data
     *
     * @param $template 模板路径
     * @param $data 替换数组
     * @return mixed
     */
    public function compile($template, $data)
    {
    	$template = app('files')->get($template);

        foreach($data as $key => $value) {
            $template = preg_replace("/\\$$key\\$/i", $value, $template);
        }

        return $template;
    }
}
