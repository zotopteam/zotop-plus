<?php

namespace App\Modules\Maker;

use App\Modules\Exceptions\TableNotFoundException;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
     *
     * @var string
     */
    protected $table;

    /**
     * Convert dbal types to Laravel Migration Types
     *
     * @var array
     */
    protected $fieldTypeMap = [
        'tinyint'    => 'tinyInteger',
        'smallint'   => 'smallInteger',
        'mediumint'  => 'mediumInteger',
        'bigint'     => 'bigInteger',
        'longtext'   => 'longText',
        'mediumtext' => 'mediumText',
        'datetime'   => 'dateTime',
        'blob'       => 'binary',
    ];

    /**
     * @var array
     */
    private $registerTypes = [
        // Mysql types
        'json'     => 'text',
        'jsonb'    => 'text',
        'bit'      => 'boolean',
        'enum'     => 'string',
        // Postgres types
        '_text'    => 'text',
        '_int4'    => 'integer',
        '_numeric' => 'float',
        'cidr'     => 'string',
        'inet'     => 'string',
    ];

    /**
     * @var array
     */
    private $customTypes = [
        // 'enum'       => 'App\Modules\Maker\Types\EnumType',
        // 'json'       => 'App\Modules\Maker\Types\JsonType',
        // 'tinytext'   => 'App\Modules\Maker\Types\TinytextType',
        'year'       => 'App\Modules\Maker\Types\YearType',
        'tinyint'    => 'App\Modules\Maker\Types\TinyintType',
        'mediumint'  => 'App\Modules\Maker\Types\MediumintType',
        'timestamp'  => 'App\Modules\Maker\Types\TimestampType',
        'longtext'   => 'App\Modules\Maker\Types\LongtextType',
        'mediumtext' => 'App\Modules\Maker\Types\MediumtextType',
    ];

    /**
     * Table constructor.
     *
     * @throws \Doctrine\DBAL\Exception
     */
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
        foreach ($this->registerTypes as $from => $to) {
            $this->schema->getDatabasePlatform()->registerDoctrineTypeMapping($from, $to);
        }
    }

    /**
     * 获取全部的数据表集合
     *
     * @param boolean $prefix 是否包含前缀
     * @return \Illuminate\Support\Collection
     */
    public static function all($prefix = false)
    {
        $instance = new static;

        $tables = $instance->schema->listTableNames();

        return collect($tables)->transform(function ($table) {
            return strtolower($table);
        })->transform(function ($table) use ($instance, $prefix) {
            return $prefix ? $table : Str::after($table, $instance->prefix);
        })->sort();
    }

    /**
     * 查找表
     *
     * @param string $table 不含前缀
     * @return object|false
     */
    public static function find(string $table)
    {
        $instance = new static;
        $instance->table = Str::after(strtolower($table), $instance->prefix);

        return $instance;
    }

    /**
     * 查找表，如果不存在，跑出异常
     *
     * @param string $table 不含前缀
     * @return mixed
     * @throws \App\Modules\Exceptions\TableNotFoundException
     */
    public static function findOrFail(string $table)
    {
        $table = static::find($table);

        if ($table->exists()) {
            return $table;
        }

        throw new TableNotFoundException("Table {$table} does not exists", 1);
    }

    /**
     * 判断表是否存在
     *
     * @return boolean
     */
    public function exists()
    {
        return Schema::hasTable($this->table);
    }

    /**
     * 获取表的全名
     *
     * @param boolean $prefix 是否含前缀
     * @return string
     */
    public function name($prefix = false)
    {
        return $prefix ? $this->prefix . $this->table : $this->table;
    }

    /**
     * 重命名数据表
     *
     * @param string $name 表名称
     * @return bool
     */
    public function rename(string $name)
    {
        Schema::rename($this->table, $name);

        if (Schema::hasTable($name)) {
            $this->table = $name;
            return true;
        }

        return false;
    }

    /**
     * 删除存在的表
     *
     * @return \Illuminate\Database\Schema\Builder|void
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function drop()
    {
        return Schema::dropIfExists($this->table);
    }

    /**
     * 获取表的信息
     *
     * @param string|null $key name:表名称(全名) comment:表注释 rows:表行数，created_at：创建时间，updated_at：修改时间
     * @return string|array
     * @author Chen Lei
     * @date 2020-11-19
     */
    public function info($key = null)
    {
        $driver = DB::connection()->getConfig('driver');
        $database = DB::connection()->getConfig('database');
        $table = $this->name(true);

        // 表信息
        $info = [
            'name' => $table,
        ];

        if ($driver == 'mysql') {
            $sql = 'SELECT * FROM information_schema.TABLES WHERE table_schema=? AND TABLE_NAME=?';
            $result = DB::selectOne($sql, [$database, $table]);
            $info['comment'] = data_get($result, 'TABLE_COMMENT');
            $info['rows'] = data_get($result, 'TABLE_ROWS');
            $info['created_at'] = data_get($result, 'CREATE_TIME');
            $info['updated_at'] = data_get($result, 'UPDATE_TIME');
        }

        return $key ? Arr::get($info, $key) : $info;
    }

    /**
     * 设置表的注释信息
     *
     * @param string $comment
     * @return bool|void
     * @author Chen Lei
     * @date 2020-11-18
     */
    public function comment($comment)
    {
        $driver = DB::connection()->getConfig('driver');
        $table = $this->name(true);

        // mysql
        if ($driver == 'mysql') {
            // 修改注释
            DB::statement('ALTER TABLE ? COMMENT ?', [$table, $comment]);
            return true;
        }

        return null;
    }

    /**
     * 获取表的字段集合
     *
     * @return \Illuminate\Support\Collection
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function columns()
    {
        $columns = $this->schema->listTableColumns($this->prefix . $this->table);

        return collect($columns)->transform(function ($column) {
            return [
                'name'       => $column->getName(),
                'type'       => $this->getColumnType($column),
                'length'     => $this->getColumnLength($column),
                'default'    => $column->getDefault(),
                'nullable'   => intval(!$column->getNotNull()),
                'unsigned'   => intval($column->getUnsigned()),
                'increments' => intval($column->getAutoincrement()),
                'comment'    => $column->getComment(),
            ];
        });
    }

    /**
     * 获取字段类型
     *
     * @param $column
     * @return string
     * @author Chen Lei
     * @date 2020-11-07
     */
    protected function getColumnType($column)
    {
        $type = $column->getType()->getName();

        if ($type == 'string' && $column->getFixed()) {
            $type = 'char';
        }

        if ($type == 'tinyint' && $column->getType()->isBoolean($this->name(true), $column->getName())) {
            $type = 'boolean';
        }

        return $type;
    }

    /**
     * 获取字段长度
     *
     * @param mixed $column 字段
     * @return mixed
     */
    protected function getColumnLength($column)
    {
        $length = $column->getLength();

        $type = $this->getColumnType($column);

        // 去除laravel中没有长度参数的类型
        if (!in_array($type, ['char', 'string', 'float', 'double', 'decimal', 'enum'])) {
            $length = null;
        }

        // 获取浮点类型的精度
        if (in_array($type, ['decimal', 'float', 'double'])) {
            $length = $column->getPrecision() . ',' . $column->getScale();
        }

        return $length;
    }

    /**
     * 获取表的索引
     *
     * @return \Illuminate\Support\Collection
     */
    public function indexes()
    {
        $indexes = $this->schema->listTableIndexes($this->prefix . $this->table);

        return collect($indexes)->transform(function ($index) {
            return [
                'name'    => $index->getName(),
                'columns' => $index->getColumns(),
                'type'    => $this->getIndexType($index),
            ];
        });
    }

    /**
     * 获取索引类型
     *
     * @param $index
     * @return string
     * @author Chen Lei
     * @date 2020-11-07
     */
    protected function getIndexType($index)
    {
        $type = 'index';

        if ($index->isUnique()) {
            $type = 'unique';
        }

        if ($index->isPrimary()) {
            $type = 'primary';
        }

        return $type;
    }

    /**
     * 获取表的外键集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function foreignKeys()
    {
        $foreignKeys = $this->schema->listTableForeignKeys($this->prefix . $this->table);

        return collect($foreignKeys)->transform(function ($foreignKey) {
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
     *
     * @param mixed $foreignKey
     * @return string|null
     */
    protected function getForeignKeyName($foreignKey)
    {
        $name = $foreignKey->getName();

        // laravel外键名称
        $column = $foreignKey->getLocalColumns()[0];
        $index = strtolower("{$this->table}_{$column}_foreign");
        $index = str_replace(['-', '.'], '_', $index);

        // 如果是默认外键，则无需返回名称
        if ($name === $index) {
            return null;
        }

        return $index;
    }

    /**
     * 获取外键名称
     *
     * @param mixed $foreignKey
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
     *
     * @return string
     */
    public function getBlueprints()
    {
        return Structure::instance($this->columns(), $this->indexes(), $this->foreignKeys())->getBluepoints();
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name();
    }
}
