<?php

namespace App\Modules\Maker;

use App\Modules\Exceptions\TableNotFoundException;
use App\Modules\Maker\Structure;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class Table
{
    /**
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $schema;

    /**
     * 数据表前缀
     *
     * @var string
     */
    protected $prefix;

    /**
     * 表名称，不含前缀
     * @var string
     */
    protected $table;

    /**
     * Convert dbal types to Laravel Migration Types
     * @var array
     */
    protected $fieldTypeMap = [
        'tinyint'   => 'tinyInteger',
        'smallint'  => 'smallInteger',
        'mediumint' => 'mediumInteger',
        'bigint'    => 'bigInteger',
        'longtext' => 'longText',
        'mediumtext' => 'mediumText',
        'datetime'  => 'dateTime',
        'blob'      => 'binary',
    ];

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
        // 'enum'          => 'App\Modules\Maker\Types\EnumType',
        // 'json'          => 'App\Modules\Maker\Types\JsonType',
        'year'          => 'App\Modules\Maker\Types\YearType',
        'tinyint'     => 'App\Modules\Maker\Types\TinyintType',
        'mediumint'     => 'App\Modules\Maker\Types\MediumintType',
        'timestamp'     => 'App\Modules\Maker\Types\TimestampType',
        'longtext'      => 'App\Modules\Maker\Types\LongtextType',
        'mediumtext'    => 'App\Modules\Maker\Types\MediumtextType',
        //'tinytext'      => 'App\Modules\Maker\Types\TinytextType',
    ];    

    public function __construct()
    {
        $this->schema = Schema::getConnection()->getDoctrineSchemaManager();
        $this->prefix = Schema::getConnection()->getTablePrefix();

       // 注册自定义的字段类型
        foreach ($this->customTypes as $type => $class) {
            
            // 注册type数组
            $this->registerTypes[$type] = $type;

            // 添加或者复写type
            if (Type::hasType($type)) {
                Type::overrideType($type, $class);
            } else {
                Type::addType($type, $class);
            }
        }

        // https://github.com/laravel/framework/issues/1346
        foreach ($this->registerTypes as $from=>$to) {
            $this->schema->getDatabasePlatform()->registerDoctrineTypeMapping($from, $to);
        }              
    }
    
    /**
     * 获取全部的数据表
     * @param  boolean $prefix 是否包含前缀
     * @return mixed
     */
    public static function all($prefix=false)
    {
        $instance  = new static;

        $tables = $instance->schema->listTableNames();

        if ($prefix == false) {
            $tables = array_map(function($table) use ($instance) {
                return Str::after($table, $instance->prefix);
            }, $tables);     
        }

        $tables = array_map('strtolower', $tables);       

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
            $instance->table = Str::after(strtolower($table), $instance->prefix);
        }

        return $instance;
    }

    /**
     * 查找表，如果不存在，跑出异常
     * @param  string $table 不含前缀
     * @return object|false
     */
    public static function findOrFail($table)
    {
        $table = static::find($table);

        if ($table->exists()) {
            return $table;
        }

        throw new TableNotFoundException("Table {$table} does not exists", 1);
    }

    /**
     * 判断表是否存在
     * @param  string $table 表名称，不含前缀
     * @return boolean
     */
    public function exists()
    {
        return Schema::hasTable($this->table);
    }

    /**
     * 获取表的全名
     * @param  boolean $prefix 是否含前缀
     * @return string
     */
    public function name($prefix=false)
    {
        return $prefix ? $this->prefix . $this->table : $this->table;
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
     * 获取表的字段集合
     * 
     * @return collection
     */
    public function columns()
    {
        $columns = $this->schema->listTableColumns($this->prefix.$this->table);

        return collect($columns)->transform(function($column) {
            return [
                'name'       => $column->getName(),
                'type'       => $this->getColumnType($column),
                'length'     => $this->getColumnLength($column),
                'default'    => $column->getDefault(),
                'nullable'   => intval(! $column->getNotNull()),
                'unsigned'   => intval($column->getUnsigned()),
                'increments' => intval($column->getAutoincrement()),
                'comment'    => $column->getComment(),              
            ];
        });
    }

    /**
     * 获取字段类型
     * @param  Column $column 字段
     * @return string
     */
    protected function getColumnType($column)
    {
        $type = $column->getType()->getName();

        if ($type == 'string' && $column->getFixed()) {
            $type = 'char';
        }

        if($type == 'tinyint' && $column->getType()->isBoolean($this->name(true), $column->getName())) {
            $type = 'boolean';
        }

        return $type;
    }

    /**
     * 获取字段长度
     * @param  Column $column 字段
     * @return mixed
     */
    protected function getColumnLength($column)
    {
        $length = $column->getLength();

        $type   = $this->getColumnType($column);

        // 去除laravel中没有长度参数的类型
        if (! in_array($type, ['char', 'string', 'float', 'double','decimal','enum'])) {
            $length = null;
        }

        // 获取浮点类型的精度
        if (in_array($type , ['decimal', 'float', 'double'])) {
            $length = $column->getPrecision().','.$column->getScale();
        }

        return $length;
    }

    /**
     * 获取表的索引
     * 
     * @return array
     */
    public function indexes()
    {
        $indexes = $this->schema->listTableIndexes($this->prefix.$this->table);

        return collect($indexes)->transform(function($index) {
            return [
                'name'    => $index->getName(),
                'columns' => $index->getColumns(),
                'type'    => $this->getIndexType($index),
            ];
        });
    }

    /**
     * 获取字段类型
     * @param  Column $column 字段
     * @return string
     */
    protected function getIndexType($index)
    {
        $type = 'index';

        if ( $index->isUnique() ) {
            $type = 'unique';
        }

        if ( $index->isPrimary() ) {
            $type = 'primary';
        }    

        return $type;
    }

    /**
     * 获取表的外键集合
     * @return [type] [description]
     */
    public function foreignKeys()
    {
        $foreignKeys = $this->schema->listTableForeignKeys($this->prefix.$this->table);

        return collect($foreignKeys)->transform(function($foreignKey) {
            return [
                'name'       => $this->getForeignKeyName($foreignKey),
                'column'     => $foreignKey->getLocalColumns()[0],
                'references' => $foreignKey->getForeignColumns()[0],
                'on'         => $this->getForeignTableName($foreignKey),
                'onUpdate'   => $foreignKey->getOption('onUpdate'),
                'onDelete'   => $foreignKey->getOption('onDelete'),
            ];            
        });
    }

    /**
     * 获取外键名称
     * @param  ForeignKey $foreignKey
     * @return string|null
     */
    protected function getForeignKeyName($foreignKey)
    {
        $name   = $foreignKey->getName();

        // laravel外键名称
        $column = $foreignKey->getLocalColumns()[0];
        $index  = strtolower("{$this->table}_{$column}_foreign");
        $index  = str_replace(array('-', '.'), '_', $index);

        // 如果是默认外键，则无需返回名称
        if ($name === $index) {
            return null;
        }

        return $index;
    }

    /**
     * 获取外键名称
     * @param  ForeignKey $foreignKey
     * @return string
     */
    protected function getForeignTableName($foreignKey)
    {
        $name = $foreignKey->getForeignTableName();
        $name = Str::after($name, $this->prefix);

        return $name;
    }

    /**
     * 获取创建字段语句
     * @return string
     */
    public function getBlueprints()
    {
        return Structure::instance($this->columns(), $this->indexes(), $this->foreignKeys())->getBluepoints();
    }       

    /**
     * Handle call __toString.
     * @return string
     */
    public function __toString()
    {
        return $this->name();
    }

}