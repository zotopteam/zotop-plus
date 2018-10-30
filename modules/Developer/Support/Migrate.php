<?php
namespace Modules\Developer\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Modules\Developer\Support\Table;
use Modules\Developer\Support\Structure;
use Modules\Developer\Support\StructureDiff;
use Module;
use Artisan;


class Migrate
{
	protected $module;
	protected $table;
	protected $structure;
	protected $filesystem;
	protected $migrations;

	public function __construct($module, $table, $structure=null)
	{
		$this->module     = Module::find($module);
		$this->table      = Table::find($table);
		$this->filesystem = app('files');
		$this->migrations = $this->module->getPath().'/Database/Migrations';
		$this->structure  = $structure;
	}

	/**
	 * 获取实例
	 * 
	 * @return this
	 */
	public static function instance($module, $table, $structure=null)
	{
		return new static($module, $table, $structure);
	}		

	/**
	 * 创建表迁移文件，如果已经存在并覆盖创建迁移，则会删除该全部迁移文件
	 * 
	 * @param  boolean $override 覆盖已有迁移文件
	 * @param  string  $template 模板路径
	 * @return bool
	 */
	public function createTable($override=false, $template=null)
	{
		$content = $this->getCreateTableMigration($template);

		// 检查是否存在迁移文件
		if ($migrations = $this->getMigrationFiles()) {

			// 如果不允许覆盖，则返回false
			if (! $override) {
				return false;
			}

			// 允许覆盖的时候，删除全部迁移文件，包含update迁移
			Artisan::call('migrate:files', [
				'files'  => $migrations,
				'--mode' => 'reset'
			]);

			array_map([$this->filesystem, 'delete'], $migrations);
		}

		$file =  tap($this->getMigrationFilePath('create'), function($file) use ($content) {
			$this->filesystem->put($file, $content);
		});

		Artisan::call('migrate:files', [
			'files'  => $file,
			'--mode' => 'migrate'
		]);

		return true;
	}

	/**
	 * 创建更新迁移
	 * @return bool
	 */
	public function updateTable($newname=null, $template=null)
	{
		$content = $this->getUpdateTableMigration($newname, $template);

		dd($content);

		// $file =  tap($this->getMigrationFilePath('update'), function($file) use ($content) {
		// 	$this->filesystem->put($file, $content);
		// });

		// Artisan::call('migrate:files', [
		// 	'files'  => $file,
		// 	'--mode' => 'migrate'
		// ]);		

		return true;
	}
	/**
	 * 删除全部迁移
	 * @return bool
	 */

	public function dropTable($template=null)
	{
		$content = $this->getDropTableMigration($template);

		if ($migrations = $this->getMigrationFiles()) {

			Artisan::call('migrate:files', [
				'files'  => $migrations,
				'--mode' => 'reset'
			]);

			array_map([$this->filesystem, 'delete'], $migrations);
		}

		$file =  tap($this->getMigrationFilePath('drop'), function($file) use ($content) {
			$this->filesystem->put($file, $content);
		});

		Artisan::call('migrate:files', [
			'files'  => $file,
			'--mode' => 'migrate'
		]);		

		return true;
	}

	/**
	 * 获取表的迁移内容
	 * 
	 * @param  string $template
	 * @return string
	 */
	public function getCreateTableMigration($template=null)
	{
		// 转换字段和索引为 laravel 的方法和属性
		if (! $this->structure) {
			$this->structure = Structure::instance($this->table->columns(), $this->table->indexes());
		}

		$bluepoints = $this->structure->convertToLaravel();

		$blueprintUp = [];

		foreach($bluepoints as $bluepoint) {
			$blueprintUp[] = $this->convertToBluepoints($bluepoint);
		}

		$blueprintUp = implode(PHP_EOL."\t\t\t", $blueprintUp);

		$template  = $template ?: __DIR__ .'/stubs/create_table.stub';
		$name = $this->getMigrationFileName('create');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'UP'    => $blueprintUp,
		];

		return $this->compile($template, $data);
	}

	/**
	 * 获取表的更新迁移内容
	 * 
	 * @param  string $template
	 * @return string
	 */
	public function getUpdateTableMigration($newname=null, $template=null)
	{
		$blueprintUp   = [];
		$blueprintDown = [];

		if ($this->table->name() != $newname) {
			$bluepoint = $this->getRenameBlueprint($this->table->name(), $newname);
			$blueprintUp[]   = $bluepoint['up'];
			$blueprintDown[] = $bluepoint['down'];
		}

		// 数据表结构
		$structure = Structure::instance($this->table->columns(), $this->table->indexes());
		
		// 获取差异更新结构
		$diffrents = StructureDiff::instance($structure, $this->structure)->get();
		
		if ($diffrents) {
			$blueprintUp[]   = "Schema::create('".$newname."', function (Blueprint \$table) {";
			$blueprintDown[] = "Schema::create('".$newname."', function (Blueprint \$table) {";
			foreach($diffrents as $action => $arguments) {
				$bluepoint = call_user_func_array([$this, 'get'.ucwords($action).'Blueprint'], $arguments);
				$blueprintUp[]   = $bluepoint['up'];
				$blueprintDown[] = $bluepoint['down'];
			}
			$blueprintUp[]   = "});";
			$blueprintDown[] = "});";
		}

		$blueprintUp = implode(PHP_EOL."\t\t\t", $blueprintUp);
		$blueprintDown = implode(PHP_EOL."\t\t\t", $blueprintDown);		

		$template  = $template ?: __DIR__ .'/stubs/update_table.stub';
		$name = $this->getMigrationFileName('update');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'UP'    => $blueprintUp,
			'DOWN'  => $blueprintDown,
		];

		return $this->compile($template, $data);
	}	

	/**
	 * 获取表的迁移内容
	 * 
	 * @param  string $template
	 * @return string
	 */
	public function getDropTableMigration($template=null)
	{
		// 转换字段和索引为 laravel 的方法和属性
		$bluepoints = Structure::instance($this->table->columns(), $this->table->indexes())->convertToLaravel();

		$blueprintDown = [];

		foreach($bluepoints as $bluepoint) {
			$blueprintDown[] = $this->convertToBluepoints($bluepoint);
		}

		$blueprintDown = implode(PHP_EOL."\t\t\t", $blueprintDown);

		$template  = $template ?: __DIR__ .'/stubs/drop_table.stub';
		$name = $this->getMigrationFileName('drop');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'DOWN'    => $blueprintDown,
		];

		return $this->compile($template, $data);
	}

	/**
	 * 重命名
	 * 
	 * @return array
	 */
	public function getRenameBlueprint($from, $to)
	{
		$blueprintUp   = sprintf("Schema::rename(%s, %s);", $this->convertToBluepointsArgument($from), $this->convertToBluepointsArgument($to));
		$blueprintDown = sprintf("Schema::rename(%s, %s);", $this->convertToBluepointsArgument($to), $this->convertToBluepointsArgument($from));

		return ['up'=>$blueprintUp, 'down'=>$blueprintDown];
	}	

	/**
	 * 获取更新迁移的内容
	 * @return array
	 */
	public function getDropColumnBlueprint($column)
	{
		// 删除字段
		$blueprintUp = sprintf("\$table->%s(%s);", 'dropColumn', $this->convertToBluepointsArgument($column['name']));

		// 还原字段
		$blueprintDown = Structure::convertColumnToLaravel($column);
		$blueprintDown = $this->convertToBluepoints($blueprintDown);

		// debug('blueprintUp', $blueprintUp);
		// debug('blueprintDown',$blueprintDown);

		return ['up'=>$blueprintUp, 'down'=>$blueprintDown];
	}

	/**
	 * 将字段和索引转换为 Blueprint 语句
	 * 
	 * @param  array $bluepoint
	 * @return string
	 */
	public function convertToBluepoints($bluepoint)
	{
		$output = '';

		$method    = $bluepoint['method'];
		$arguments = $bluepoint['arguments'];
		$modifiers = $bluepoint['modifiers'] ?? [];

		// 转换参数
		$arguments = array_map([$this, 'convertToBluepointsArgument'], $arguments);
		
		if (count($arguments) == 3) {
			$output = sprintf("\$table->%s(%s, %s, %s)", $method, $arguments[0], $arguments[1], $arguments[2]);
		}

		if (count($arguments) == 2) {
			$output = sprintf("\$table->%s(%s, %s)", $method, $arguments[0], $arguments[1]);
		}

		if (count($arguments) == 1) {
			$output = sprintf("\$table->%s(%s)", $method, $arguments[0]);
		}

		// 修改器
		foreach ($modifiers as $modifier => $argument) {
			if ($argument) {
				$output .= sprintf("->%s(%s)", $modifier, $this->convertToBluepointsArgument($argument[0]));
			} else {
				$output .= sprintf("->%s()", $modifier);
			}
		}

		$output .= ';';

		// debug($column);
		// debug($output);

		return $output;
	}

	/**
	 * 参数转换
	 * 
	 * @param  [type] $argument [description]
	 * @return [type]           [description]
	 */
	public function convertToBluepointsArgument($argument)
	{
		if (is_array($argument)) {
			return "['".implode("','", $argument)."']";
		}

		if (is_numeric($argument)) {
			return $argument;
		}

		return "'".$argument."'";		
	}	

	/**
	 * 获取Migration文件名
	 * @param  string $type create | update
	 * @return string
	 */
	public function getMigrationFileName($type = 'realname')
	{
		static $names = [];

		if (! isset($names[$type])) {

			if ($type == 'create') {
				$name = 'create_'. $this->table->name() .'_table_'.time();
			} else if ($type == 'update') {
				$name = 'update_'. $this->table->name() .'_table_'.time();
			} else if ($type == 'drop') {
				$name = 'drop_'. $this->table->name() .'_table_'.time();				
			} else {
				$name = $this->table->name() .'_table';
			}

			$names[$type] = $name;
		}

		return $names[$type];		
	}

	/**
	 * 获取Migration文件路径
	 * @param  string $type create | update
	 * @return string
	 */
	public function getMigrationFilePath($type)
	{
		$name = $this->getMigrationFileName($type);
		$path = $this->migrations.'/'.date('Y_m_d_His', strtotime( '+1 second' )).'_'.$name.'.php';
		return $path;
	}

	/**
	 * 获取已经存在的迁移文件
	 * 
	 * @param  string $type create | update
	 * @return string
	 */
	public function getMigrationFiles($type = null)
	{
		$files = [];

		// 获取全部的迁移文件
		$name = $this->getMigrationFileName($type);
		
		foreach ($this->filesystem->files($this->migrations) as $file) {
            if (strpos($file, $name)) {
                $files[] = (string)$file;
            }
        }

        return $files;	
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
    	$template = $this->filesystem->get($template);

        foreach($data as $key => $value) {
            $template = preg_replace("/\\$$key\\$/i", $value, $template);
        }

        return $template;
    }	
}
