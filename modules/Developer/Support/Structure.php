<?php
namespace Modules\Developer\Support;


class Structure
{
	/**
	 * 表名称
	 * @var string
	 */
	protected $table;

	/**
	 * 字段
	 * @var collection
	 */
	protected $columns;

	/**
	 * 索引
	 * @var collection
	 */
	protected $indexes;

	/**
	 * 自增
	 * @var collection
	 */
	protected $increments;

	/**
	 * 字段
	 * @var collection
	 */
	protected $columnDefault = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];


	/**
	 * @param string $database
	 * @param bool   $ignoreIndexNames
	 * @param bool   $ignoreForeignKeyNames
	 */
	public function __construct(array $columns, array $indexes)
	{
		// $this->table = $table;

		$this->columns = collect($columns)->filter(function($column) {
			return $this->validColumn($column);
		})->map(function($column){
			return $this->formatColumn($column);
		});

		$this->indexes = collect($indexes)->filter(function($index) {
			return $this->validIndex($index);
		})->map(function($index) {
			return $this->formatIndex($index);
		});

		debug($this->indexes->all());
	}

	/**
	 * 获取columns集合
	 * @return collection
	 */
	public function columns()
	{
		return $this->columns;
	}

	/**
	 * 获取indexes集合
	 * @return collection
	 */
	public function indexes()
	{
		return $this->indexes;
	}

	/**
	 * 获取自增字段名称
	 * @return string
	 */
	public function increments()
	{
		return $this->columns->where('increments','increments')->first()['name'];
	}

	/**
	 * 验证column是否有效
	 * @param  mixed $column
	 * @return bool
	 */
	public function validColumn($column)
	{	
		// 包含 name 和 type 不为空的
		if (is_array($column)) {
			$column = array_where($column, function($item) {
				return !empty($item);
			});
			return array_has($column, ['name','type']);
		}

		return false;
	}

	/**
	 * 是否已经存在字段
	 * @param  mixed $column 字段数组或者字段名称
	 * @return bool
	 */
	public function existsColumn($column)
	{
		$name = is_array($column) ? ($column['name'] ?? null) : $column;

		if ($name && $this->columns->where('name', $name)->count() > 0 ) {
			return true;
		}

		return false;
	}	

	/**
	 * 验证index是否有效
	 * @param  mixed $index
	 * @return bool
	 */
	public function validIndex($index)
	{
		if (is_array($index)) {

			// 去掉index中的空值
			$index = array_where($index, function($item) {
				return !empty($item);
			});

			// 索引结构正确
			if (array_has($index, ['type','columns']) && in_array($index['type'], ['primary', 'index', 'unique'])) {

		        // 如果有自增，则主键索引无效
		        if ($this->increments() && $index['type'] == 'primary') {
		            return false;
		        }

				// 格式化索引字段
				$columns = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
				
				// 如果columns字段中存在对应的索引字段，索引有效
				$names = $this->columns->pluck('name')->all();

				if (! array_diff($columns, $names)) {
					return true;
				}			
			}
		}

		return false;
	}

	/**
	 * 是否已经存在索引，
	 * 
	 * @param  mixed $index 索引数组（已经检查过为有效索引）
	 * @return bool
	 */
	public function existsIndex($index)
	{	
        // 如果有自增，则不能添加主键索引
        if ($this->increments() && $index['type'] == 'primary') {
            return true;
        }

        $columns = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
        
        // 判断索引字段是否一致
        $exists = $this->indexes->filter(function($item) use ($columns) {
        	return count($item['columns']) == count($columns) && !array_diff($item['columns'], $columns);
        });

        //debug($exists->all());

        if ($exists->count()) {
        	return true;
        }
        
		return false;
	}	

	/**
	 * 格式化column
	 * @param  array $column
	 * @return array
	 */
	public function formatColumn(array $column)
	{
		if (in_array($column['name'], ['created_at','updated_at', 'deleted_at'])) {
			$column['type'] = 'timestamp';
		}

		return array_merge($this->columnDefault, $column);
	}

	/**
	 * 格式化index
	 * @param  array $index
	 * @return array
	 */
	public function formatIndex(array $index)
	{
		$index['columns'] = is_array($index['columns']) ? array_values($index['columns']) : explode(',', $index['columns']);
		$index['columns'] = array_sort(array_unique($index['columns']));

		if ($index['type'] == 'primary') {
			$index['name'] = 'PRIMARY';
		} else {
			$index['name'] = implode('_', $index['columns']);
		}

		return $index;
	}	

	/**
	 * 添加新字段
	 * @return this 
	 */
	public function addColumn($column)
	{
		if ($this->validColumn($column)) {

			if ($this->existsColumn($column)) {
				abort(403, trans('developer::table.column.exists'));
			}

			$column = $this->formatColumn($column);
			$this->columns->push($column);
			return true;
		}

		return false;
	}

	/**
	 * 添加空白行
	 */
	public function addBlank()
	{
		$this->columns->push($this->columnDefault);
		return true;
	}

	/**
	 * 添加时间戳
	 */
	public function addTimestamps()
	{
		// 检查 created_at 和 updated_at 是否已经存在
		if ($this->existsColumn('created_at') && $this->existsColumn('updated_at')) {
		    abort(403, trans('developer::table.column.exists'));
		}

        $this->addColumn(['name'=>'created_at','type'=>'timestamp']);
        $this->addColumn(['name'=>'updated_at','type'=>'timestamp']);

        return true;
	}

	/**
	 * 添加软删除
	 */
	public function addSoftdeletes()
	{
        $this->addColumn(['name'=>'deleted_at','type'=>'timestamp']);
        return true;
	}

	/**
	 * 删除字段
	 * @param  mixed $column 字段数组或者字段名称
	 * @return bool
	 */
	public function dropColumn($column)
	{
		$name = is_array($column) ? ($column['name'] ?? null) : $column;

		$this->columns->filter(function($column) use($name) {
			return $column['name'] != $name;
		});

		return true;
	}

	/**
	 * 添加索引
	 * @param array $index
	 * @return bool
	 */
	public function addIndex($index)
	{
		if ($this->validIndex($index)) {
			
			if ($this->existsIndex($index)) {
				abort(403, trans('developer::table.index.exists'));
			}

			$index = $this->formatIndex($index);
			$this->indexes->push($index);
			return true;			
		}

		return false;
	}

	
}
