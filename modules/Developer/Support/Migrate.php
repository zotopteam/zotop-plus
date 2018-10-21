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

	public function __construct($module, $table)
	{
		$this->module     = Module::find($module);
		$this->table      = Table::find($table);
		$this->filesystem = app('files');
		$this->dir        = dirname(__DIR__).'/Database/Migrates/'.$module;
		$this->filepath   = $this->dir.'/'.$table.'.php';
		$this->migrations = $this->module->getPath().'/Database/Migrations';		
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

	public function createTableMigration($override=false, $template=null)
	{
		$migrations = $this->getMigrationFiles('create');

		if ($migrations && $override) {
			array_map([$this->filesystem, 'delete'], $migrations);
		}

		$this->filesystem->put(
			$this->getMigrationFilePath('create'),
			$this->getTableMigration($template)
		);
		
		return true;
	}

	public function getTableMigration($template=null)
	{
		$blueprintUp = [];

		// 创建字段
		$columns = $this->table->columns();
		$indexes = $this->table->indexes();

		$bluepoints = $this->table->convertColumnsIndexes($columns, $indexes);
		$bluepoints = array_merge($bluepoints['columns'], $bluepoints['indexes']);

		$bluepointsUp = [];

		foreach($bluepoints as $bluepoint) {
			$bluepointsUp[] = $this->convertBluepointToString($bluepoint);
		}

		$bluepointsUp = implode(PHP_EOL."\t\t\t", $bluepointsUp);

		$template  = $template ?: __DIR__ .'/stubs/create_table.stub';
		$name = $this->getMigrationFileName('create');

		$data = [
			'CLASS' => ucwords(camel_case($name)),
			'TABLE' => $this->table->name(),
			'UP'    => $bluepointsUp,
		];

		return $this->compile($template, $data);
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

		$arguments = array_map(function($argument) {
			if (is_array($argument)) {
				return "['".implode("','", $argument)."']";
			}
			if (is_numeric($argument)) {
				return $argument;
			}
			return "'".$argument."'";
		}, $arguments);
		
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
			if ($argument === true) {
				$output .= sprintf("->%s()", $modifier);
			} else if ($argument) {
				$output .= sprintf("->%s('%s')", $modifier, $argument);
			}
		}

		$output .= ';';

		// debug($column);
		// debug($output);

		return $output;
	}

	/**
	 * 将字段转换为 Blueprint 语句
	 * @param  array $column
	 * @return string
	 */
	public function convertIndexToBlueprint($index)
	{
		if (count($index['columns']) > 1) {
			$output = sprintf("\$table->%s(['%s'],'%s');", $index['type'], implode("','", $index['columns']), $index['name']);
		} else {
			$output = sprintf("\$table->%s('%s','%s');", $index['type'], $index['columns'][0], $index['name']);
		}

		// debug($index);
		// debug($output);

		return $output;
	}		

	/**
	 * 获取Migration文件名
	 * @param  string $type create | update
	 * @return string
	 */
	public function getMigrationFileName($type)
	{
		if ($type == 'create') {
			$name = 'create_'. $this->table->name() .'_table';
		}
		
		if ($type == 'update') {
			$name = 'update_'. $this->table->name() .'_table';
		}

		return $name;		
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
	 * @param  string $type create | update
	 * @return string
	 */
	public function getMigrationFiles($type)
	{
		$files = [];

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
