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
     * 从属性数组中弹出值
     * 
     * @param  array $options 属性数组
     * @param  string $key    要弹出的键名
     * @param  mixed $default 默认值
     * @return mixed
     */
    private function options_pull(&$options, $key, $default=null)
    {
        if (isset($options[$key])) {
            
            $value = array_pull($options, $key);

            return is_array($default) ? (array)$value + $default : $value;
        }

        return $default;      
    }


    /**
     * Open up a new HTML form.
     *
     * @param  array $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function open(array $options = [])
    {
        // 绑定数据
        if ( isset($options['model']) ) {
            $this->model = array_pull($options, 'model');
        }

        // 表单默认样式
        $options['class'] = isset($options['class']) ? 'form '.$options['class'] : 'form';

        return parent::open($options);
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
        $type = method_exists($this, $type) ? $type : 'text';

        // 按钮
        if ( in_array($type, ['submit','cancel'.'reset','button']) ) {
            $name  = array_pull($options, 'name');
            $value = array_pull($options, 'value');
            return $this->button($value, $options);
        }

        // 调用已经存在的字段
        if ( in_array($type, ['text','textarea','hidden','email','tel','number','date','datetime','datetimeLocal','time','url','color']) ) {
            $name  = array_pull($options,'name');
            $value = array_pull($options, 'value');
            return $this->$type($name, $value, $options);
        }

        if ( in_array($type, ['file','password']) ) {
            $name  = array_pull($options, 'name');
            $value = array_pull($options, 'value');
            return $this->$type($name, $options);
        }

        if ( in_array($type, ['select','selectRange']) ) {
            $name  = array_pull($options, 'name');
            $value = array_pull($options, 'value');           
            $list  = array_pull($options, 'options', []);
            return $this->$type($name, $list, $value, $options);
        }

        if ( in_array($type, ['radio','checkbox']) ) {
            $name    = array_pull($options, 'name');
            $value   = array_pull($options, 'value');           
            $checked = array_pull($options, 'checked', null);            
            //$checked = in_array(strtolower($checked),['checked','true','1']) ? true : false;// 此处待斟酌，应该有更好的实现方式 by hankx_chen 2017年4月5日
            return $this->$type($name, $value, $checked, $options);
        }

        return $this->$type($options);        
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
        if (array_key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        return $name;
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
    public function select($name, $list = [], $selected = null, array $options = [], array $attrs = [])
    {
        $options['class'] = empty($options['class']) ? 'form-control' : 'form-control '.$options['class'];

        return parent::select($name, $list, $selected, $options, $attrs);            
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


    /**
     * 手机号字段
     * 
     * @param  array  $attrs 标签数组
     * @return mixed
     */
    public function mobile(Array $attrs)
    {
        $name  = array_pull($attrs,'name');
        $value = $this->getValue($attrs);

        return $this->input('text', $name, $value, $attrs);
    }

}
