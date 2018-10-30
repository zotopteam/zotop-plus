<?php
namespace Modules\Developer\Support;


class StructureDiff
{
	protected $old;
	protected $new;
	protected $diff;

	/**
	 * 初始化
	 * @param Structure $old 原结构
	 * @param Structure $new 新结构
	 */
	public function __construct($old, $new)
	{
		$this->old  = $old;
		$this->new  = $new;
		$this->diff = collect([]);
	}

	/**
	 * 获取实例
	 * 
	 * @param array $columns 字段数组
	 * @param array $indexes 索引数组
	 * @return this
	 */
	public static function instance($old, $new)
	{
		return new static($old, $new);
	}	

	/**
	 * 获取不同的结果集合
	 * 
	 * @return collection
	 */
	public function get()
	{
		$oldColumns = $this->old->columns()->map(function($item) {
			return $this->formatColumn($item);
		});

		$newColumns = $this->new->columns()->map(function($item) {
			return $this->formatColumn($item);
		});

		// 新结构的key中不存在于老结构key中的为新增字段
		$addColumns    = $newColumns->diffKeys($oldColumns)->each(function($item, $key) {
			$this->diff->push(['method'=>'addColumn', 'arguments'=>['column'=>$item]]);
		});

		// 老结构的key中不存在于新结构key中的为删除字段
		$dropColumns   = $oldColumns->diffKeys($newColumns)->each(function($item, $key) {
			$this->diff->push(['method'=>'dropColumn', 'arguments'=>['column'=>$item]]);
		});

		// 新结构中key不等于name的为重命名字段，(去除新增字段)
		$renameColumns = $newColumns->filter(function($item, $key) use ($addColumns) {

			if ($key != $item['name'] && !in_array($item['name'], $addColumns->pluck('name')->all())) {
				$this->diff->push(['method'=>'renameColumn', 'arguments'=>['from'=>$key, 'to'=>$item['name']]]);
				return true;
			}

			return false;
		});

		// 新结构中属性改变的为修改属性字段，如果字段在老字段中，且排除重命名的情况下字段结构不一致，为修改字段
		$changeColumns = $newColumns->filter(function($item, $key) use ($oldColumns) {

			$oldColumn = $oldColumns->filter(function($i, $k) use ($key) {
				return $k == $key;
			})->first();

			// 如果字段存在，且排除重命名的情况下字段结构不一致，那么为修改字段属性
			if ($oldColumn && array_except($oldColumn,'name') != array_except($item, 'name')) {
				$this->diff->push(['method'=>'changeColumn', 'arguments'=>['from'=>$oldColumn, 'to'=>$item]]);
				return true;
			}

			return false;
		});

		dd($this->diff);

		return $this->diff;
	}

	/**
	 * 格式化字段，重排顺序
	 * 
	 * @param  [type] $column [description]
	 * @return [type]         [description]
	 */
	public function formatColumn($column)
	{
		$format = [];
		
		foreach (['name','type', 'length', 'nullable', 'unsigned', 'increments', 'default', 'comment'] as $key) {
			$format[$key] = $column[$key] ?? null;
		}

		return $format;
	}
}
