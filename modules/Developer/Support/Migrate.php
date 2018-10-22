<?php
namespace Modules\Developer\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Modules\Developer\Support\Table;
use Module;


class Migrate
{
	protected $module;
	protected $table;
	protected $filesystem;
	protected $dir;
	protected $filepath;
	protected $migrations;
	protected $migrationsTable;

	public function __construct($module, $table)
	{
		$this->module     = Module::find($module);
		$this->table      = Table::find($table);
		$this->filesystem = app('files');
		$this->dir        = dirname(__DIR__).'/Database/Migrates/'.$module;
		$this->filepath   = $this->dir.'/'.$table.'.php';
		$this->migrations = $this->module->getPath().'/Database/Migrations';
		$this->migrationsTable = app('db')->table(config('database.migrations'));
	}

	/**
	 * 获取修改记录
	 * 
	 * @return [type] [description]
	 */
	public function get()
	{
		$data = [];

		if ($this->filesystem->exists($this->filepath)) {
			$data = require $this->filepath;
			$data = is_array($data) ? $data : [];
		}

		return $data;
	}	

	/**
	 * 保存修改记录
	 * 
	 * @return [type] [description]
	 */
	public function put(array $item)
	{		
        // 如果不存在，尝试创建
        if (!$this->filesystem->isDirectory($dir = $this->dir)) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

		// 追加新元素
		$data = $this->get();

		array_push($data, $item + [
			'created_at' => (string)now()
		]);

		// 如果是重命名，删除原有的文件，创建新名文件
		if ($item['action'] == 'rename') {
			$this->filesystem->delete($this->filepath);
			$this->filepath   = $this->dir.'/'.$item['to'].'.php';
		}

        $this->filesystem->put($this->filepath, "<?php\nreturn ".var_export($data, true).";");

        return true;
	}

	/**
	 * 删除迁移日志
	 * 
	 * @return bool
	 */
	public function delete()
	{
		// 删除迁移日志
		$this->filesystem->delete($this->filepath);

		// 日志文件夹为空时，删除文件夹
		if (! $this->filesystem->files($this->dir)) {
			$this->filesystem->deleteDirectory($this->dir);
		}

		return true;
	}

	/**
	 * 常见表迁移文件
	 * 
	 * @param  boolean $override 覆盖已有迁移文件
	 * @param  string  $template 模板路径
	 * @return bool
	 */
	public function createTableMigration($override=false, $template=null)
	{
		$migrations = $this->getMigrationFiles();

		if ($migrations) {

			// 如果不允许覆盖，则返回false
			if (! $override) {
				return false;
			}
			
			// 允许覆盖的时候，删除全部迁移文件，包含update迁移
			array_map([$this->filesystem, 'delete'], $migrations);

			// 允许覆盖时候，删除更新日志文件
			$this->delete();
		}

		$this->filesystem->put(
			$this->getMigrationFilePath('create'),
			$this->getCreateTableMigration($template)
		);
		
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
		$blueprintUp = [];

		// 创建字段
		$columns = $this->table->columns();
		$indexes = $this->table->indexes();

		$bluepoints = $this->table->convertColumnsIndexes($columns, $indexes);
		$bluepoints = array_merge($bluepoints['columns'], $bluepoints['indexes']);

		$blueprintUp = [];

		foreach($bluepoints as $bluepoint) {
			$blueprintUp[] = $this->convertBluepointToString($bluepoint);
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
	 * 生成更新迁移
	 * @return bool
	 */
	public function updateTableMigration($template=null)
	{
		$this->filesystem->put(
			$this->getMigrationFilePath('update'),
			$this->getUpdateTableMigration($template)
		);

		// 删除更新日志文件
		$this->delete();	

		return true;
	}

	/**
	 * 获取更新迁移的内容
	 * @return array
	 */
	public function getUpdateTableMigration($template=null)
	{
		$blueprintUp   = [];
		$blueprintDown = [];

		$update_list = $this->get();
		
		foreach($update_list as $update) {
			$method    = 'get'.ucfirst($update['action']).'Blueprint';
			$arguments = $update['arguments'];

			$bluepoints = call_user_func_array([$this, $method], $arguments);

			$blueprintUp[]   = $bluepoints['blueprintUp'];
			$blueprintDown[] = $bluepoints['blueprintDown'];
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
	 * 获取更新迁移的内容
	 * @return array
	 */
	public function getDropColumnBlueprint($column)
	{
		// 删除字段
		$blueprintUp = sprintf("\$table->%s(%s);", 'dropColumn', $this->convertArgument($column['name']));

		// 还原字段
		$blueprintDown = $this->table->convertColumn($column);
		$blueprintDown = $this->convertBluepointToString($blueprintDown);

		// debug('blueprintUp', $blueprintUp);
		// debug('blueprintDown',$blueprintDown);

		return ['blueprintUp'=>$blueprintUp, 'blueprintDown'=>$blueprintDown];
	}

	/**
	 * 将字段和索引转换为 Blueprint 语句
	 * 
	 * @param  array $bluepoint
	 * @return string
	 */
	public function convertBluepointToString($bluepoint)
	{
		$output = '';

		$method    = $bluepoint['method'];
		$arguments = $bluepoint['arguments'];
		$modifiers = $bluepoint['modifiers'] ?? [];

		// 转换参数
		$arguments = array_map([$this, 'convertArgument'], $arguments);
		
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
				$output .= sprintf("->%s(%s)", $modifier, $this->convertArgument($argument[0]));
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
	public function convertArgument($argument)
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
				$name = 'create_'. $this->table->name() .'_table';
			} else if ($type == 'update') {
				$name = 'update_'. $this->table->name() .'_table_'.date('YmdHis');
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
                $files[] = $file;
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
