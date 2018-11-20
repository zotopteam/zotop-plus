<?php
namespace Modules\Content\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Doctrine\DBAL\Types\Type;
use Modules\Content\Models\Model;
use Modules\Content\Models\Field;
use Filter;

class ModelTable
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
     * 当前模型编号
     * 
     * @var string
     */
    private $model_id;
    /**
     * 模型数据表前缀
     * @var string
     */
    private $model_table_prefix = 'content_model_';

	/**
	 * @var array
	 */
    private $registerTypes = [
    	// Mysql types
        'json'      => 'text',
        'jsonb'     => 'text',
        'bit'       => 'boolean',
        'enum'      => 'string',      
        // Postgres types
        '_text'     => 'text',
        '_int4'     => 'integer',
        '_numeric'  => 'float',
        'cidr'      => 'string',
        'inet'      => 'string',
    ];

    /**
     * @var array
     */
    private $customTypes = [

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

        // 注册自定义的字段类型
        foreach (Filter::fire('table.custom.types', $this->customTypes) as $type => $class) {
            $this->registerTypes[$type] = $type;
            if (! Type::hasType($type)) {
                Type::addType($type, $class);
            } else {
                Type::overrideType($type, $class);
            }
        }

        // https://github.com/laravel/framework/issues/1346
        foreach (Filter::fire('table.register.types', $this->registerTypes) as $from=>$to) {
            $schema->getDatabasePlatform()->registerDoctrineTypeMapping($from, $to);
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
				return str_after($table, $instance->prefix.$instance->model_table_prefix);
			}, $tables);	
		}

		return $tables;        
	}

	/**
	 * 查找表
	 * @param  string $model_id 模型编号
	 * @return object|false
	 */
	public static function find($model_id)
	{
		static $instance = null;

		if (empty($instance)) {
			$instance = new static;
			$instance->model_id = $model_id;
            $instance->table    = $instance->model_table_prefix.$model_id;
		}

		return $instance;
	}

    /**
     * 获取表名称
     * 
     * @param  boolean $prefix 是否包含前缀
     * @return string
     */
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
	public function rename($model_id)
	{
        // 新表名称
        $newtable = $this->model_table_prefix.$model_id;

        if (Schema::hasTable($newtable)) {
            abort(403, trans('core.master.exists', [$newtable]));
        }

        if (Schema::hasTable($this->table)) {
            Schema::rename($this->table, $newtable);
            Model::where('id', $this->model_id)->update(['table'=>$newtable]);
        }

        $this->model_id = $model_id;
        $this->table    = $newtable;
        return true;
	}

	/**
	 * 删除表是否存在
	 * 
	 * @return bool
	 */
	public function drop()
	{
		Schema::dropIfExists($this->table);

        return true;        
	}

	/**
	 * 创建表
	 * 
	 * @param array $columns 字段
	 * @param array  $indexes 索引
	 * @return bool
	 */
	public function create()
	{
		Schema::create($this->table, function (Blueprint $table) {
            //创建时自动创建自增id字段和content_id字段
			$table->integer('id')->unsigned()->primary('id')->comment('Content id');
            //外键约束，删除主表数据时，自动删除从表
            $table->foreign('id')->references('id')->on('content')->onDelete('cascade');
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
		return Schema::getColumnListing($this->table);
	}

	/**
	 * 删除字段
	 * 
	 * @param  string $column 字段名称
	 * @return bool
	 */
	public function dropColumn($column)
	{
		Schema::table($this->table, function (Blueprint $table) use ($column) {
		    $table->dropColumn($column);
		});

        // 当前扩展表字段数为1的时候，自动删除扩展表
        if (count($this->columns()) == 1) {
            $this->drop();
        }

		return true;	
	}

    /**
     * 删除字段
     * 
     * @param  string $column 字段名称
     * @return bool
     */
    public function renameColumn($column, $newname)
    {
        if (Schema::hasColumn($this->table, $newname)) {
            abort(403, trans('content::field.name.exists', [$newname]));
        }

        Schema::table($this->table, function (Blueprint $table) use ($column, $newname) {
            $table->renameColumn($column, $newname);
        });

        return true;    
    }    

    /**
     * 新增字段
     * 
     * @param  object $field 字段
     * @return bool
     */
    public function addColumn($field)
    {
        if (Schema::hasColumn($this->table, $field->name)) {
            abort(403, trans('content::field.name.exists', [$field->name]));
        }

        // 自动创建
        if (! $this->exists()) {
            $this->create();
        }

        $bluepoint = $this->fieldToLaravel($field);

        return $this->bluepoint($bluepoint);    
    }

    /**
     * 修改字段
     * 
     * @param  object $field 字段
     * @return bool
     */
    public function changeColumn($field)
    {
        $bluepoint = $this->fieldToLaravel($field, true);

        return $this->bluepoint($bluepoint); 
    }

    /**
     * 执行bluepoint
     * @param  [type] $bluepoint [description]
     * @return [type]            [description]
     */
    private function bluepoint($bluepoint)
    {
        Schema::table($this->table, function (Blueprint $table) use ($bluepoint) {

            $method    = $bluepoint['method'];
            $arguments = $bluepoint['arguments'];
            $modifiers = $bluepoint['modifiers'] ?? [];
            
            // 执行方法
            $result = call_user_func_array([$table, $method], $arguments);

            // 修改器
            foreach ($modifiers as $modifier => $argument) {
                call_user_func_array([$result, $modifier], $argument);
            }
        });

        return true;
    }

	/**
	 * 转换字段
	 * 
	 * @return array
	 */
	public function fieldToLaravel($field, $change=false)
	{
        $convert = [];

        // 方法
        $convert['method'] = $field->method;

        // 字段类型方法的第一个参数总是字段名称
        $convert['arguments'] = [$field->name];

        // 存储修改器，方法名称=>参数数组
        $convert['modifiers'] = [];

        // 如果非必填，则允许空
        if (array_get($field->settings, 'required', 0)) {
            $convert['modifiers']['nullable'] = [false];
        } else {
            $convert['modifiers']['nullable'] = [];
        }

        // 默认值
        if ($field->default) {
            $convert['modifiers']['default'] = [$field->default];
        } else {
            $convert['modifiers']['default'] = [null];
        }

        // 注释
        $convert['modifiers']['comment'] = [$field->label];

        // 数字类型，数字类型在Laravel中不能设置长度，只能按照类型长度
        if (in_array($convert['method'], ['boolean', 'tinyInteger', 'smallInteger', 'integer', 'bigInteger', 'mediumInteger'])) {
            $convert['modifiers']['default'] = [intval($field->default)];

            if (array_get($field->settings, 'min') >= 0) {
                $convert['modifiers']['unsigned'] = [];
            }
        }

        // 浮点类型的参数返回数组  [10,2] 或者数字 10 (精度默认2) ，允许浮点
        if (in_array($convert['method'], ['decimal', 'float', 'double'])) {

            $convert['arguments'][] = array_get($field->settings, 'total', 8);
            $convert['arguments'][] = array_get($field->settings, 'places', 2);

            $convert['modifiers']['default']  = [floatval($field->default)];

            if (array_get($field->settings, 'min') >= 0.00) {
                $convert['modifiers']['unsigned'] = [];
            }           
        }

        // 字符串类型如果设置了长度，加入长度参数，超出长度自动转换类型
        if (in_array($convert['method'], ['string', 'char'])) {
            $length = (int)array_get($field->settings, 'maxlength');
            
            if ($length) {
                if ($length <= 255) {
                    $convert['arguments'][]  = intval($length);
                } else {
                    $convert['method'] = 'text'; 
                }
            }
        }

        // 字符串类型如果设置了长度，加入长度参数，超出长度自动转换类型
        if (in_array($convert['method'], ['text'])) {
            $length = (int)array_get($field->settings, 'maxlength');
            
            if ($length && $length <= 255) {
                $convert['method'] = 'string';
                $convert['arguments'][]  = intval($length);
            }           
        }

        // change
        if ($change) {
            $convert['modifiers']['change'] = [];
        }        

        return $convert;
	}

    /**
     * __toString 返回数据表名称
     * @return string
     */
	public function __toString()
	{
		return $this->table;
	}
}
