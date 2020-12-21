<?php

namespace Modules\Content\Support;

use Illuminate\Support\Arr;
use Modules\Content\Models\Field;
use Modules\Content\Models\Model;

class ModelForm
{
    /**
     * 模型id
     *
     * @var string
     */
    public $model_id;

    /**
     * 模型字段数据
     *
     * @var collection
     */
    public $fields;

    /**
     * 主区域字段
     *
     * @var array
     */
    public $main;

    /**
     * 侧边区域字段
     *
     * @var array
     */
    public $side;


    /**
     * 默认值
     *
     * @var array
     */
    public $default = [];

    /**
     * 获取表单
     *
     * @param string $model_id 模型编号
     * @return ModelForm
     */
    public static function get($model_id, $source_id)
    {
        $instance = new static;
        $instance->fields = Field::where('model_id', $model_id)->orderby('sort', 'asc')->get();

        // 获取主区域字段
        $instance->main = $instance->fields->filter(function ($item) {
            return $item['position'] == 'main';
        })->values()->transform(function ($item) use ($source_id) {
            return static::convert($item, $source_id);
        });

        // 获取侧边区域字段
        $instance->side = $instance->fields->filter(function ($item) {
            return $item['position'] == 'side';
        })->values()->transform(function ($item) use ($source_id) {
            return static::convert($item, $source_id);
        });

        // 获取全部字段的默认值
        $instance->default = $instance->fields->filter(function ($item) {
            return trim($item['default']) != '';
        })->pluck('default', 'name')->toArray();

        return $instance;
    }

    /**
     * 合并默认值到对象
     *
     * @param Model $content 内容实例
     * @return object
     */
    public function default($content)
    {
        foreach ($this->default as $key => $value) {
            $content->$key = $value;
        }

        return $content;
    }

    /**
     * 转换字段格式
     *
     * @return array
     */
    public static function convert($item, $source_id)
    {
        $convert = [];

        // 基本属性
        $convert['label'] = $item->label;
        $convert['help'] = $item->help;
        $convert['for'] = $item->name;
        $convert['required'] = (bool)array_get($item->settings, 'required');
        $convert['disabled'] = (bool)$item->disabled;
        $convert['width'] = ($item->position == 'side') ? 'w-100' : $item->width;

        // 字段属性
        $convert['field']['name'] = $item->name;
        $convert['field']['type'] = $item->type;
        $convert['field']['source_id'] = $source_id;

        foreach ($item->settings as $key => $val) {

            if (in_array($key, ['min', 'max', 'minlength', 'maxlength'])) {
                $val = intval($val);
            }

            if (in_array($key, ['options']) && in_array($item->type, ['select', 'radiogroup', 'checkboxgroup'])) {
                $val = static::convertOptions($val);
            }

            if (in_array($key, ['resize', 'watermark']) && in_array($val, [0, 1])) {
                $val = (bool)$val;
            }

            if (in_array($key, ['required'])) {
                if (empty($val)) {
                    continue;
                }
                $val = $key;
            }

            $convert['field'][$key] = $val;
        }

        return $convert;
    }

    /**
     * 转化options为标准格式
     *
     * @param array $options
     * @return array
     */
    public static function convertOptions($options)
    {
        $convert = [];

        foreach ((array)$options as $option) {
            Arr::set($convert, $option['value'], $option['text']);
        }

        return $convert;
    }
}
