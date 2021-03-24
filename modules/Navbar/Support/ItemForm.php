<?php

namespace Modules\Navbar\Support;

use Illuminate\Support\Arr;
use Modules\Navbar\Models\Field;

class ItemForm
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $fields;


    /**
     * 表单初始化
     *
     * @param int $navbar_id
     * @param int $parent_id
     * @author Chen Lei
     * @date 2021-02-03
     */
    public function __construct(int $navbar_id, int $parent_id)
    {
        $this->fields = Field::where('navbar_id', $navbar_id)->where('parent_id', $parent_id)->get();
    }

    /**
     * 表单实例
     *
     * @param int $navbar_id
     * @param int $parent_id
     * @return static
     * @author Chen Lei
     * @date 2021-02-03
     */
    public static function instance(int $navbar_id, int $parent_id)
    {
        return new static($navbar_id, $parent_id);
    }

    /**
     * 获取字段
     *
     * @return \Illuminate\Support\Collection
     * @author Chen Lei
     * @date 2021-02-03
     */
    public function fields()
    {
        return $this->fields->transform(function ($item) {
            return static::convert($item);
        });
    }

    /**
     * 合并默认值到对象
     *
     * @return array
     */
    public function default()
    {
        return $this->fields->filter(function ($item) {
            return trim($item['default']) != '';
        })->pluck('default', 'name')->toArray();
    }

    /**
     * 转换字段格式
     *
     * @param \Modules\Navbar\Models\Field $item
     * @return array
     */
    public static function convert(Field $item)
    {
        $convert = [];

        // 基本属性
        $convert['label'] = $item->label;
        $convert['help'] = $item->help;
        $convert['for'] = "custom-{$item->name}";
        $convert['required'] = (bool)Arr::get($item->settings, 'required');
        $convert['disabled'] = (bool)$item->disabled;

        // 字段属性
        $convert['field']['name'] = "custom[{$item->name}]";
        $convert['field']['type'] = $item->type;

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
    public static function convertOptions(array $options)
    {
        $convert = [];

        foreach ($options as $option) {
            Arr::set($convert, $option['value'], $option['text']);
        }

        return $convert;
    }
}
