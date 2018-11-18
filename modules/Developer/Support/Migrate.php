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

			// 允许覆盖的时候，删除全部迁移文件，包含update迁移，只保留最终生成的create迁移
			Artisan::call('migrate:files', [
				'files'  => $migrations,
				'--mode' => 'reset'
			]);

			array_map([$this->filesystem, 'delete'], $migrations);
		}

		// 生成迁移文件
		$file = tap($this->getMigrationFilePath('create'), function($file) use ($content) {
			$this->filesystem->put($file, $content);
		});

		try {
			// 迁移文件
			Artisan::call('migrate:files', [
				'files'  => $file,
				'--mode' => 'migrate-refresh'
			]);				
		} catch (Exception $e) {
			// 迁移失败，删除生成的迁移文件
			$this->filesystem->delete($file);
			abort(500, $e->getMessage());
		}

		return true;
	}

	/**
	 * 创建更新迁移
	 * @return bool
	 */
	public function updateTable($newname=null, $template=null)
	{
		$content = $this->getUpdateTableMigration($newname, $template);

		if ($content) {

			// 生成迁移文件
			$file =  tap($this->getMigrationFilePath('update'), function($file) use ($content) {
				$this->filesystem->put($file, $content);
			});

			try {
				// 迁移文件
				Artisan::call('migrate:files', [
					'files'  => $file,
					'--mode' => 'migrate-refresh'
				]);	
			} catch (\Exception $e) {
				// 迁移失败，删除迁移文件
				$this->filesystem->delete($file);
				abort(500, $e->getMessage());
			}

			return true;
		}		

		return null;
	}
	/**
	 * 删除全部迁移
	 * @return bool
	 */

	public function dropTable($template=null)
	{
		$content = $this->getDropTableMigration($template);
 
		// 删除表时，回滚所有该表已经迁移的文件，并删除该表的迁移文件
		if ($migrations = $this->getMigrationFiles()) {

			Artisan::call('migrate:files', [
				'files'  => $migrations,
				'--mode' => 'reset'
			]);

			array_map([$this->filesystem, 'delete'], $migrations);
		}

		// 生成迁移文件
		$file = tap($this->getMigrationFilePath('drop'), function($file) use ($content) {
			$this->filesystem->put($file, $content);
		});

		try {
			// 迁移文件
			Artisan::call('migrate:files', [
				'files'  => $file,
				'--mode' => 'migrate-refresh'
			]);		
		} catch (\Exception $e) {
			$this->filesystem->delete($file);
			abort(500, $e->getMessage());
		}

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

		$up = [];

		foreach($bluepoints as $bluepoint) {
			$up[] = $this->convertToBluepoints($bluepoint);
		}

		$up = implode(PHP_EOL."\t\t\t", $up);

		$template  = $template ?: __DIR__ .'/stubs/create_table.stub';
		$name = $this->getMigrationFileName('create');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'UP'    => $up,
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
		// 未传入表的新名称时，为不改变表名称
		if (empty($newname)) {
			$newname = $this->table->name();
		}

		// 数据表结构
		$structure = Structure::instance($this->table->columns(), $this->table->indexes());
		
		// 获取差异更新结构
		$diffrents = StructureDiff::instance($structure, $this->structure)->get();
		
		$up        = $this->getUpdateTableUpBlueprint($diffrents, $newname);
		$down      = $this->getUpdateTableDownBlueprint($diffrents, $newname);

		if ($up && $down) {

			$up = implode(PHP_EOL."\t\t", $up);
			$down = implode(PHP_EOL."\t\t", $down);		

			$template  = $template ?: __DIR__ .'/stubs/update_table.stub';
			$name = $this->getMigrationFileName('update');

			$data = [
				'CLASS' => ucwords(camel_case($name)),
				'TABLE' => $this->table->name(),
				'UP'    => $up,
				'DOWN'  => $down,
			];

			return $this->compile($template, $data);
		}

		return null;
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

		$down = [];

		foreach($bluepoints as $bluepoint) {
			$down[] = $this->convertToBluepoints($bluepoint);
		}

		$down = implode(PHP_EOL."\t\t\t", $down);

		$template = $template ?: __DIR__ .'/stubs/drop_table.stub';
		$name     = $this->getMigrationFileName('drop');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'DOWN'  => $down,
		];

		return $this->compile($template, $data);
	}

	public function getUpdateTableUpBlueprint($diffrents, $newname)
	{
		$bluepoint = [];

		// 重命名表
		if ($this->table->name() != $newname) {
			$bluepoint[] = $this->getRenameBlueprint($this->table->name(), $newname)."\r\n";
		}

		if ($diffrents->count()) {

			// 由于laravel 的 某些schema 无法一次性执行，所以改为每次执行一种类型
			$codes = [];

			$diffrents->where('action', 'dropIndex')->each(function($diff) use(&$codes) {
				$codes['dropIndex'][] = $this->getDropIndexBlueprint($diff['index']);
			});

			$diffrents->where('action', 'dropColumn')->each(function($diff) use(&$codes) {
				$codes['dropColumn'][] = $this->getDropColumnBlueprint($diff['column']);
			});

			$diffrents->where('action', 'renameColumn')->each(function($diff) use(&$codes) {
				$codes['renameColumn'][] = $this->getRenameColumnBlueprint($diff['from'], $diff['to']);
			});

			$diffrents->where('action', 'addColumn')->each(function($diff) use(&$codes) {
				$codes['addColumn'][] = $this->getAddColumnBlueprint($diff['column']);
			});			

			$diffrents->where('action', 'addIndex')->each(function($diff) use(&$codes) {
				$codes['addIndex'][] = $this->getAddIndexBlueprint($diff['index']);
			});

			$diffrents->where('action', 'changeColumn')->each(function($diff) use(&$codes) {
				$codes['changeColumn'][] = $this->getChangeColumnBlueprint($diff['to']);
			});

			foreach ($codes as $action=>$code) {
				$bluepoint[] = "//".$action;
				$bluepoint[] = "Schema::table('".$newname."', function (Blueprint \$table) {";
				foreach ($code as $c) {
					$bluepoint[] = "\t".$c;
				}
				$bluepoint[] = "});\r\n";
			}
			
		}

		return $bluepoint;
	}

	public function getUpdateTableDownBlueprint($diffrents, $newname)
	{
		$bluepoint = [];

		// 重命名表
		if ($this->table->name() != $newname) {
			$bluepoint[] = $this->getRenameBlueprint($newname, $this->table->name())."\r\n";;
		}

		if ($diffrents->count()) {

			$codes = [];

			$diffrents->where('action', 'dropColumn')->each(function($diff) use(&$codes) {
				$codes['dropColumn'][] = $this->getAddColumnBlueprint($diff['column']);
			});

			$diffrents->where('action', 'dropIndex')->each(function($diff) use(&$codes) {
				$codes['dropIndex'][] = $this->getAddIndexBlueprint($diff['index']);
			});

			$diffrents->where('action', 'addIndex')->each(function($diff) use(&$codes) {
				$codes['addIndex'][] = $this->getDropIndexBlueprint($diff['index']);
			});

			$diffrents->where('action', 'addColumn')->each(function($diff) use(&$codes) {
				$codes['addColumn'][] = $this->getDropColumnBlueprint($diff['column']);
			});

			$diffrents->where('action', 'renameColumn')->each(function($diff) use(&$codes) {
				$codes['renameColumn'][] = $this->getRenameColumnBlueprint($diff['to'], $diff['from']);
			});

			$diffrents->where('action', 'changeColumn')->each(function($diff) use(&$codes) {
				$codes['changeColumn'][] = $this->getChangeColumnBlueprint($diff['from']);
			});				

			foreach ($codes as $action=>$code) {
				$bluepoint[] = "//".$action;
				$bluepoint[] = "Schema::table('".$this->table->name()."', function (Blueprint \$table) {";
				foreach ($code as $c) {
					$bluepoint[] = "\t".$c;
				}
				$bluepoint[] = "});\r\n";
			}
		}

		return $bluepoint;
	}

	/**
	 * 重命名数据表
	 * 
	 * @return string
	 */
	public function getRenameBlueprint($from, $to)
	{
		$bluepoint = sprintf("Schema::rename(%s, %s);", $this->convertToBluepointsArgument($from), $this->convertToBluepointsArgument($to));

		return $bluepoint;
	}	

	/**
	 * 获取删除迁移语句
	 * 
	 * @param  array $column 完整字段数组
	 * @return array
	 */
	public function getDropColumnBlueprint($column)
	{
		// 删除字段
		$bluepoint = sprintf("\$table->%s(%s);", 'dropColumn', $this->convertToBluepointsArgument($column['name']));
		return $bluepoint;
	}

	/**
	 * 获取新增迁移语句
	 *
	 * @param  array $column 完整字段数组
	 * @return array
	 */	
	public function getAddColumnBlueprint($column)
	{
		// 新增字段
		$column = Structure::convertColumnToLaravel($column);
		$bluepoint = $this->convertToBluepoints($column);

		return $bluepoint;		
	}

	/**
	 * 重命名字段迁移语句
	 * 
	 * @param  string $from 字段原名称
	 * @param  string $to   字段新名称
	 * @return array
	 */
	public function getRenameColumnBlueprint($from, $to)
	{
		$bluepoint   = sprintf("\$table->%s(%s, %s);", 'renameColumn', $this->convertToBluepointsArgument($from), $this->convertToBluepointsArgument($to));

		return $bluepoint;
	}

	/**
	 * 更新字段属性字段迁移语句
	 * 
	 * @param  string $from 字段原名称
	 * @param  string $to   字段新名称
	 * @return array
	 */	
	public function getChangeColumnBlueprint($column)
	{
		// up
		$bluepoint = Structure::convertColumnToLaravel($column, true);
		$bluepoint = $this->convertToBluepoints($bluepoint);

		return $bluepoint;	
	}

	/**
	 * 删除索引迁移语句
	 * @param  array $index 索引数组
	 * @return string
	 */
	public function getDropIndexBlueprint($index)
	{
		// up
		$bluepoint = sprintf("\$table->%s(%s);", 'drop'.ucwords($index['type']), $this->convertToBluepointsArgument($index['name']));

		return $bluepoint;
	}

	/**
	 * 添加索引迁移语句
	 * @param  array $index 索引数组
	 * @return string
	 */
	public function getAddIndexBlueprint($index)
	{
		$index = Structure::convertIndexToLaravel($index);
		$bluepoint = $this->convertToBluepoints($index);	
		
		return $bluepoint;
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

		if (is_bool($argument)) {
			return $argument ? 'true' : 'false';
		}

		if (is_numeric($argument)) {
			return $argument;
		}

		if ($argument === null) {
			return 'null';
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
