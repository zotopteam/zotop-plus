<?php
namespace Modules\Content\Support;

use Modules\Content\Models\Model;
use Modules\Content\Models\Field;
use Form;

class ModelForm
{
    /**
     * 模型id
     * @var string
     */
    public $model_id;

    /**
     * 模型字段数据
     * @var collection
     */
    public $fields;

    /**
     * 主区域字段
     * @var array
     */
    public $main;

    /**
     * 侧边区域字段
     * @var array
     */    
    public $side;


    /**
     * 默认值
     * @var array
     */    
    public $default = [];

    /**
     * 获取表单
     * 
     * @param  [type] $model_id [description]
     * @return [type]           [description]
     */
    public static function get($model_id)
    {
        $instance  = new static;
        $instance->fields = Field::where('model_id', $model_id)->orderby('sort','asc')->get();

        $instance->main = $instance->fields->filter(function($item) {
            return intval($item['col']) == 0;
        })->values()->transform(function($item) {
            return static::convert($item);
        });

        $instance->side = $instance->fields->filter(function($item) {
            return intval($item['col']) == 1;
        })->values()->transform(function($item) {
            return static::convert($item);
        });

        $instance->default = $instance->fields->filter(function($item) {
            return trim($item['default']) != '';
        })->pluck('default', 'name')->toArray();                

        return $instance;
    }

    /**
     * 合并默认值到对象
     * @param  object $object 对象
     * @return object
     */
    public function default($object)
    {
        foreach ($this->default as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }

    /**
     * 转换字段格式
     * @return array
     */
    public static function convert($item)
    {
        $convert = [];

        $convert['label']    = $item->label;
        $convert['help']     = $item->help;
        $convert['for']      = $item->name;        
        $convert['required'] = (bool)array_get($item->settings, 'required');
        $convert['disabled'] = (bool)$item->disabled;

        $convert['field']['name']  = $item->name;
        $convert['field']['type']  = Form::findType(
            'content_'.$item->model_id.'_'.$item->type,
            'content_'.$item->type,
            $item->type
        );

        foreach($item->settings as $key=>$val) {

            if (in_array($key, ['min','max','minlength','maxlength'])) {
                $val = intval($val);
            }

            if (in_array($key, ['options']) && in_array($item->type, ['select','radiogroup', 'checkboxgroup'])) {
                $val = static::convertOptions($val);
            }

            if (in_array($key, ['resize','watermark'])) {
                if (in_array($val, [0, 1])) {
                    $val = (bool)$val;
                }
                // TODO 数组格式
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
     * @param  array $options
     * @return array
     */
    public static function convertOptions($options)
    {
        $convert = [];

        foreach ((array)$options as $option) {
            array_set($convert, $option['value'], $option['text']);
        }

        return $convert;
    }     
}
