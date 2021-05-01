<?php

namespace Zotop\Modules\Maker;

use Illuminate\Support\Arr;

class Structure
{

    /**
     * 字段
     *
     * @var \Illuminate\Support\Collection
     */
    public $columns;

    /**
     * 索引
     *
     * @var \Illuminate\Support\Collection
     */
    public $indexes;

    /**
     * 外键
     *
     * @var \Illuminate\Support\Collection
     */
    public $foreignKeys;

    /**
     * 字段默认格式
     *
     * @var array
     */
    protected $columnFormatDefault = [
        'name'       => '',
        'type'       => 'string',
        'length'     => '',
        'nullable'   => 0,
        'unsigned'   => 0,
        'increments' => 0,
        'default'    => '',
        'comment'    => '',
        'index'      => '',
    ];

    /**
     * 数据库字段类型 对应的 laravel 迁移函数
     *
     * @var array
     */
    protected $columnTypeMap = [
        'tinyint'    => 'tinyInteger',
        'smallint'   => 'smallInteger',
        'mediumint'  => 'mediumInteger',
        'bigint'     => 'bigInteger',
        'mediumtext' => 'mediumText',
        'longtext'   => 'longText',
        'datetime'   => 'dateTime',
        'blob'       => 'binary',
    ];

    /**
     * 自增类型转换
     *
     * @var array
     */
    protected $incrementsMap = [
        'tinyInteger'   => 'tinyIncrements',
        'smallInteger'  => 'smallIncrements',
        'mediumInteger' => 'mediumIncrements',
        'bigInteger'    => 'bigIncrements',
        'integer'       => 'increments',
    ];

    /**
     * 初始化函数
     *
     * @param array|\Illuminate\Support\Collection $columns 字段集合
     * @param array|\Illuminate\Support\Collection $indexes 索引集合
     * @param array|\Illuminate\Support\Collection $foreignKeys 外键集合
     */
    public function __construct($columns, $indexes = [], $foreignKeys = [])
    {
        $this->columns = collect($columns)->map(function ($column) {
            return $this->formatColumn($column);
        });

        $this->indexes = collect($indexes)->map(function ($index) {
            return $this->formatIndex($index);
        })->filter(function ($index) {
            return !empty($index['columns']);
        });

        $this->foreignKeys = collect($foreignKeys);
    }

    /**
     * 获取实例
     *
     * @param array|\Illuminate\Support\Collection $columns 字段数组
     * @param array|\Illuminate\Support\Collection $indexes 索引数组
     * @param array|\Illuminate\Support\Collection $foreignKeys
     * @return $this
     */
    public static function instance($columns, $indexes = [], $foreignKeys = [])
    {
        return new static($columns, $indexes, $foreignKeys);
    }

    /**
     * 获取字段集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * 获取索引集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function indexes()
    {
        return $this->indexes;
    }

    /**
     * 获取外键集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function foreignKeys()
    {
        return $this->foreignKeys;
    }

    /**
     * 获取自增的column名称
     *
     * @return mixed
     */
    public function increments()
    {
        if ($column = $this->columns->where('increments', 1)->first()) {
            return $column['name'];
        }

        return null;
    }

    /**
     * 获取主键名称
     *
     * @return array
     */
    public function primary()
    {
        if ($index = $this->indexes->where('type', 'primary')->first()) {
            return $index['columns'];
        }

        return [];
    }

    /**
     * 格式化column
     *
     * @param array $column
     * @return array
     */
    public function formatColumn(array $column)
    {
        $column = array_merge($this->columnFormatDefault, $column);

        // 转化创建时间、更新时间和删除时间为 timestamp 类型
        if (in_array($column['name'], ['created_at', 'updated_at', 'deleted_at'])) {
            $column['type'] = 'timestamp';
        }

        // 去掉不允许长度的 length 属性
        if (!in_array($column['type'], ['char', 'string', 'float', 'double', 'decimal', 'enum'])) {
            $column['length'] = null;
        }

        // 格式化'nullable', 'unsigned', 'increments'为数字类型，json传递布尔值会转换为字符串导致错误
        foreach (['nullable', 'unsigned', 'increments'] as $key) {
            $column[$key] = intval($column[$key]);
        }

        return $column;
    }

    /**
     * 格式化index
     *
     * @param array $index
     * @return array
     */
    public function formatIndex(array $index)
    {
        // 从index过滤掉不存在的字段
        $columns = $this->columns->pluck('name')->all();

        foreach ($index['columns'] as $key => $column) {
            if (!in_array($column, $columns)) {
                unset($index['columns'][$key]);
            }
        }

        // 复合索引字段排序键名重置
        $index['columns'] = Arr::sort(array_unique($index['columns']));
        $index['name'] = implode('_', $index['columns']);

        // 主键处理
        if ($index['type'] == 'primary') {
            // Laravel 的自增已经添加了主键
            $increments = $this->increments();
            if ($increments && $increments != $index['name']) {
                $index['columns'] = [$increments];
            }
            $index['name'] = 'PRIMARY';
        }

        return $index;
    }

    /**
     * 通过名称找寻字段
     *
     * @param string $name 字段名称
     * @return array
     */
    public function getColumn(string $name)
    {
        if ($column = $this->columns->where('name', $name)->first()) {
            return $column;
        }

        return [];
    }

    /**
     * 添加字段
     *
     * @param array $column 字段数组
     * @return \Illuminate\Support\Collection
     */
    public function addColumn(array $column)
    {
        $column = $this->formatColumn($column);

        return $this->columns->push($column);
    }

    /**
     * 删除字段
     *
     * @param string $name 字段数组或者字段名称
     * @return \Illuminate\Support\Collection
     */
    public function dropColumn(string $name)
    {
        return $this->columns->filter(function ($column) use ($name) {
            return $column['name'] != $name;
        });
    }

    /**
     * 添加索引
     *
     * @param array $index
     * @return \Illuminate\Support\Collection
     */
    public function addIndex(array $index)
    {
        $index = $this->formatIndex($index);
        return $this->indexes->push($index);
    }

    /**
     * 转化为创建语句
     *
     * @return string
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function getBluepoints()
    {
        // 分离单索引和复合索引
        [$singeIndexes, $multipleIndexes] = $this->indexes->partition(function ($index) {
            return count($index['columns']) == 1;
        });

        // 字段转换
        $columns = $this->columns->map(function ($column) use ($singeIndexes) {
            // 获取字段单个索引
            $index = $singeIndexes->filter(function ($index) use ($column) {
                return $column['name'] == $index['columns'][0];
            });
            // 单索引追加index类型字段
            $column['index'] = $index->isNotEmpty() ? $index->first()['type'] : null;
            return $this->convertColumn($column);
        })->values();

        // 复合索引转换
        $indexes = $multipleIndexes->map(function ($index) {
            return $this->convertIndex($index);
        })->values();

        // 外键转换
        $foreignKeys = $this->foreignKeys->map(function ($foreignKey) {
            return $this->convertForeignKey($foreignKey);
        })->values();

        // 空白行
        $blank = collect(['']);

        return $columns->merge($blank)
            ->merge($indexes)->merge($blank)
            ->merge($foreignKeys)
            ->implode(PHP_EOL . "            ");
    }

    /**
     * 将类型type转化为laravel支持的函数名称
     *
     * @param array $column
     * @return mixed|string
     */
    public function getColumnMethod(array $column)
    {
        $type = $column['type'];
        $method = $this->columnTypeMap[$type] ?? $type;

        if (in_array($method, array_keys($this->incrementsMap)) && $column['increments']) {
            $method = $this->incrementsMap[$method];
        }

        return $method;
    }

    /**
     * 转换字段为 bluepoint 字符串
     *
     * @param array $column
     * @return string
     */
    public function convertColumn(array $column)
    {
        // 定义方法组，键名为方法名称，键值为方法参数
        $convert = [];

        // 主方法从字段类型转化为laravel的方法名称，例如，string,bigInteger,smallText
        $method = $this->getColumnMethod($column);

        // 可用的字段类型方法的第一个参数总是字段名称
        $convert[$method] = [$column['name']];

        // 自增类型的主键不能有 unsigned,nullable,default,index,unique,primary 等
        if (!$column['increments']) {

            if ($column['unsigned']) {
                $convert['unsigned'] = [];
            }

            if ($column['nullable']) {
                $convert['nullable'] = [];
            }

            if (!is_null($column['default'])) {
                $convert['default'] = [$column['default']];
            }

            //   'text','mediumText','longText' 类型字段不使用索引
            if ($column['index'] && !in_array($method, ['text', 'mediumText', 'longText'])) {
                $convert[$column['index']] = [$column['name']];
            }

            // 字符串类型如果设置了长度，加入长度参数
            if (in_array($method, ['string', 'char']) && intval($column['length'])) {
                $convert[$method][] = intval($column['length']);
            }

            // 浮点类型的参数返回数组  [10,2] 或者数字 10 (精度默认2) ，允许浮点
            if (in_array($method, ['decimal', 'float', 'double'])) {

                if ($column['length']) {
                    [$total, $places] = explode(',', $column['length'] . ',2');
                    $convert[$method][] = intval($total);
                    $convert[$method][] = intval($places);
                }

                if (!is_null($column['default'])) {
                    $convert['default'] = [floatval($column['default'])];
                }
            }

            // 数字类型，默认值必须是数字
            if (in_array($method, ['boolean', 'tinyInteger', 'smallInteger', 'integer', 'bigInteger', 'mediumInteger'])) {
                if (!is_null($column['default'])) {
                    $convert['default'] = [intval($column['default'])];
                }
            }
        }

        if ($column['comment']) {
            $convert['comment'] = [$column['comment']];
        }

        return $this->convertToBluepoint($convert);
    }

    /**
     * 转换索引为 bluepoint 字符串
     *
     * @param array $index 索引
     * @return string
     */
    public function convertIndex(array $index)
    {
        // Laravel方法的参数：primary, index, unique 函数的最多允许两个参数
        // 单一索引第一个参数为字段名称，符合索引第一个参数为字段名称数组
        $arguments = [
            (count($index['columns']) == 1) ? reset($index['columns']) : $index['columns'],
        ];

        // 第二个参数为索引名称 primary 类型不能设置名称
        if ($index['type'] != 'primary') {
            $arguments[] = $index['name'];
        }

        // 方法名称：primary, index, unique
        $convert[$index['type']] = $arguments;

        return $this->convertToBluepoint($convert);
    }

    /**
     * 转换外键
     *
     * @param array $foreignKey
     * @return string
     */
    public function convertForeignKey(array $foreignKey)
    {
        $convert = [];

        $convert['foreign'] = [$foreignKey['column']];
        $convert['references'] = [$foreignKey['references']];
        $convert['on'] = [$foreignKey['on']];

        if (!empty($foreignKey['name'])) {
            $convert['foreign'][] = $foreignKey['name'];
        }

        if ($foreignKey['onUpdate']) {
            $convert['onUpdate'] = [$foreignKey['onUpdate']];
        }

        if ($foreignKey['onDelete']) {
            $convert['onDelete'] = [$foreignKey['onDelete']];
        }


        return $this->convertToBluepoint($convert);
    }

    /**
     * 将方法组转换为 Blueprint 语句
     *
     * @param array $convert
     * @return string
     */
    public function convertToBluepoint(array $convert)
    {
        if (empty($convert)) {
            return null;
        }

        $output = "\$table";

        foreach ($convert as $method => $arguments) {
            $arguments = array_map([$this, 'convertBluepointArgument'], $arguments);
            $output .= sprintf("->%s(%s)", $method, implode(', ', $arguments));
        }

        $output .= ';';

        return $output;
    }

    /**
     * 参数转换
     *
     * @param mixed $argument
     * @return string
     */
    public function convertBluepointArgument($argument)
    {
        if (is_array($argument)) {
            return "['" . implode("', '", $argument) . "']";
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

        return "'" . $argument . "'";
    }
}
