<?php
namespace Modules\Content\Support;

use Modules\Content\Models\Model;
use Modules\Content\Models\Field;

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
     * 隐藏字段
     * @var array
     */      
    public $hidden;

    /**
     * 初始化
     */
    public function __construct()
    {

    }

    /**
     * [get description]
     * @param  [type] $model_id [description]
     * @return [type]           [description]
     */
    public static function get($model_id)
    {
        $instance  = new static;
        $instance->fields = Field::where('model_id', $model_id)->orderby('sort','asc')->get();

        $instance->main = $instance->fields->filter(function($item){
            return $item['col'] == 0 && $item['disabled'] == 0;
        })->values()->transform(function($item) {
            return static::convert($item);
        });

        $instance->side = $instance->fields->filter(function($item){
            return $item['col'] == 1 && $item['disabled'] == 0;
        })->values()->transform(function($item) {
            return static::convert($item);
        });

        $instance->hidden = $instance->fields->filter(function($item){
            return $item['disabled'] == 1;
        })->values()->transform(function($item) {
            return static::convert($item);
        });                        

        return $instance;
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

        $convert['field']['name']  = $item->name;
        $convert['field']['type']  = $item->type;
        //$convert['field']['value'] = $item->default;

        foreach($item->settings as $key=>$val) {

            if (in_array($key, ['min','max','minlength','maxlength'])) {
                $val = intval($val);
            }

            if (in_array($key, ['options'])) {
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
