<?php

namespace Modules\Core\Base;

use Collective\Html\FormBuilder as LaravelFormBuilder;

/**
 * 表单创建助手
 * 
 * @package App\Http
 */
class FormBuilder extends LaravelFormBuilder
{

    /**
     * Open up a new HTML form.
     *
     * @param  array $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function open(array $options = [])
    {
        // 绑定模型
        if ( isset($options['model']) ) {
            $this->model = array_pull($options, 'model');
        }

        if ( $referer = array_pull($options, 'referer') ) {
            $referer = $referer == true ? request()->referer() : $referer;
            $referer = $this->hidden('_referer', $referer);
        }

        // 表单默认样式
        $options['class'] = isset($options['class']) ? 'form '.$options['class'] : 'form';

        return parent::open($options).$referer;
    }

    /**
     * 检查类型是否存在
     * 
     * @param  string  $type 类型名称
     * @return boolean
     */
    public function hasType($type)
    {
        return $this->hasMacro($type) || method_exists($this, $type);
    }

    /**
     * 依次查找类型，直到找到，找不到返回text
     * @param  array $types 类型
     * @return string
     */
    public function findType(...$types)
    {
        foreach ($types as $type) {
            if ($this->hasType($type)) {
                return $type;
            }
        }

        return 'text';
    }

    /**
     * 统一字段的调用方式 by hankx_chen
     * 
     * @param  array  $options 字段属性
     * @return html
     */
    public function field(array $options = [])
    {
        // 取出全局属性
        $type = array_get($options, 'type', 'text');

        // 如果有扩展定义，优先调用扩展定义方法
        if ( $this->hasMacro($type) ) {
            return $this->macroCall($type, [$options]);
        }

        // 如果不存在的，直接显示text
        $type  = method_exists($this, $type) ? $type : 'text';
        $name  = array_pull($options, 'name');
        $value = array_pull($options, 'value');

        if ( in_array($type, ['submit','cancel'.'reset','button']) ) {
            return $this->button($value, $options);
        }

        if ( in_array($type, ['file','password']) ) {
            return $this->$type($name, $options);
        }

        if ( in_array($type, ['select','selectRange']) ) {        
            $list  = array_pull($options, 'options', []);
            return $this->$type($name, $list, $value, $options);
        }

        if ( in_array($type, ['radio','checkbox']) ) {       
            $checked = array_pull($options, 'checked', null);            
            return $this->$type($name, $value, $checked, $options);
        }

        return $this->$type($name, $value, $options);        
    }

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string $name
     * @param  array  $attributes
     *
     * @return string
     */
    public function getIdAttribute($name, $attributes)
    {
        // 解决原函数中name为数组(含有[])的bug
        $id = '';

        if (array_key_exists('id', $attributes)) {
            $id = $attributes['id'];
        } else {
            $id = $name;
        }

        return str_replace(['.', '[]', '[', ']'], ['-', '', '-', ''], $id);
    }

    /**
     * 从标签中获取ID
     * 
     * @param  array  $attributes 标签数据
     * @param  string  $default 默认值
     * @return string
     */
    public function getId($attributes, $default = null)
    {
        if (array_key_exists('name', $attributes)) {
            return static::getIdAttribute($attributes['name'], $attributes); 
        }
        
        return $default;      
    }

    /**
     * 从属性数组中取出值
     * 
     * @param  array $options 属性数组
     * @param  mixed $key    要获取的键名，如果是数组，则为可能项，依次获取，直到获取到
     * @param  mixed $default 默认值
     * @param  mixed $pull 是否从原数组中删除
     * @return mixed
     */
    private function getAttribute(&$options, $key, $default=null, $pull=true)
    {
        // 处理多个可能的键名
        if (is_array($key)) {
            foreach ($key as $k) {
                if (array_has($options, $k)) {
                    return static::getAttribute($options, $k, $default, $pull);
                }
            }
        }

        // 如果存在键名，取出
        if (array_has($options, $key)) {
            // 是否从原数组中删除
            $value = $pull ? array_pull($options, $key) : array_get($options, $key);
            // value 为数组的情况下深度合并默认值
            if (is_array($value) && is_array($default)) {
                $value = array_merge_deep($default, $value);
            }
            return $value;
        }

        return $default;      
    }


    /**
     * 从标签数组里面获取value项
     * 
     * @param  array  $attrs 标签数组
     * @return mixed
     */
    public function getValue(Array $attrs, $value=null)
    {
        $name = array_get($attrs, 'name');

        // 从数组里面弹出
        if (isset($attrs['value'])) {
            $value = array_pull($attrs, 'value');
        }

        return $this->getValueAttribute($name, $value);
    }

    /**
     * 获取数据编号
     * @return [type] [description]
     */
    public function getDataId(Array $attrs, $default=null)
    {
        // 从标签中获取数据编号
        $data_id = array_pull($attrs, 'data_id', $default);

        // 获取可能的数据编号
        $data_id = $this->getValueAttribute('data_id', $data_id);

        if (is_null($data_id)) {
            $data_id = tap(md5(request()->fullUrl()), function($data_id) {
                session(['data_id'=>$data_id]);
            });
        }

        return $data_id;
    }


    /**
     * Override parent ,add default class
     *
     * @param  string $type
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function input($type, $name, $value = null, $options = [])
    {   
        $options['class'] = empty($options['class']) ? 'form-control' : 'form-control '.$options['class'];

        return parent::input($type, $name, $value, $options);     
    }

    /**
     * Override parent ,add default class
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function textarea($name, $value = null, $options = [])
    {
        $options['class'] = empty($options['class']) ? 'form-control' : 'form-control '.$options['class'];

        return parent::textarea($name, $value, $options);          
    }

    /**
     * Override parent ,add default class
     *
     * @param  string $name
     * @param  array  $list
     * @param  string $selected
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function select($name, $list = [], $selected = null, array $options = [], array $attrs = [], array $optgroups = [])
    {
        $options['class'] = empty($options['class']) ? 'form-control' : 'form-control '.$options['class'];

        return parent::select($name, $list, $selected, $options, $attrs, $optgroups);            
    }     

    /**
     * Override parent ,add default class
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value = null, $options = [])
    {
        $options['type']  = $options['type'] ?: 'button';        
        $options['class'] = empty($options['class']) ? "form-{$options['type']}" : "form-{$options['type']} ".$options['class'];
        
        return parent::button($value, $options); 
    }

}
