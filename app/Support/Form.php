<?php

namespace App\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class Form
{
    use Macroable, ForwardsCalls {
        Macroable::__call as macroCall;
    }

    /**
     * app 实例
     *
     * @var Html
     */
    protected $app;


    /**
     * view
     *
     * @var View
     */
    protected $view;

    /**
     * 表单绑定的数组或者实例
     *
     * @var Html
     */
    protected $bind;

    /**
     * 字段类型
     * @var array
     */
    protected $types = [
        'input'  => ['hidden', 'text', 'number', 'password', 'email', 'url', 'tel', 'date', 'datetime', 'time', 'month', 'week', 'range', 'file', 'color', 'search'],
        'button' => ['button', 'submit', 'reset'],
    ];

    /**
     * 表单追加项
     * @var array
     */
    protected $append = [];

    /**
     * 表单默认的类名
     * @var string
     */
    protected $formDefaultClass = 'form';

    /**
     * 字段默认的类名
     * @var string
     */
    protected $fieldDefaultClass = 'form-control';

    /**
     * 按钮字段
     * @var string
     */
    protected $fieldButtonClass = [
        'submit' => 'btn btn-primary',
        'button' => 'btn btn-secondary',
        'reset'  => 'btn btn-light',
    ];

    /**
     * 按钮字段
     * @var string
     */
    protected $fieldButtonIcon = [
        'submit' => 'fa fa-save',
        'button' => 'fa fa-check-circle',
        'reset'  => 'fa fa-undo',
    ];

    /**
     * 创建一个表单实例
     *
     * @param  app
     */
    public function __construct(Application $app)
    {
        $this->app  = $app;
        $this->view = $app['view'];
    }

    /**
     * 生成 <form ……> 标签
     *
     * @param  array $options
     * @return \Illuminate\Support\HtmlString
     */
    public function open(array $options = [])
    {
        // 键名小写
        $options = array_change_key_case($options);

        // 绑定模型的数组或者实例
        $this->bind = Arr::pull($options, 'bind', null);

        // 传递表单来源
        if ($referer = Arr::pull($options, 'referer')) {
            $this->append[] = $this->hidden(['name' => '_referer', 'value' => $referer]);
        }

        $attributes = [
            'method'         => $this->formMethod($options),
            'action'         => $this->formAction($options),
            'enctype'        => $this->formEnctype($options),
            'class'          => Arr::pull($options, 'class', $this->formDefaultClass),
            'accept-charset' => 'UTF-8',
        ];

        $attributes = array_merge($attributes, $options);

        $append = implode("\r\n", $this->append);

        return '<form ' . $this->attributes($attributes) . '>' . "\r\n" . $append;
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close()
    {
        $this->append = [];
        $this->bind = null;

        return $this->toHtmlString('</form>');
    }

    /**
     * 表单方法
     * @param  array $options
     * @return string
     */
    protected function formMethod(&$options)
    {
        $method = Arr::pull($options, 'method', 'post');
        $method = strtoupper($method);

        // 如果是['DELETE', 'PATCH', 'PUT']方法之一，附加为表单隐藏域
        if (in_array($method, ['DELETE', 'PATCH', 'PUT'])) {
            $this->append[] = $this->hidden(['name' => '_method', 'value' => $method]);
        }

        // 追加token
        if ($method !== 'GET') {
            $this->append[] = $this->token();
            return 'POST';
        }

        return 'GET';
    }

    /**
     * 表单方法
     * @param  array $options
     * @return string
     */
    protected function formAction(&$options)
    {
        $keys = ['url', 'route', 'action'];

        if ($attributes = Arr::only($options, $keys)) {

            // 从属性中删除'url', 'route', 'action'
            Arr::forget($options, $keys);

            foreach ($attributes as $method => $parameter) {
                // ['route.name', 'parameter1', 'parameter2'……]
                if (is_array($parameter)) {
                    return call_user_func_array($method, [$parameter[0], array_slice($parameter, 1)]);
                }
                // ‘route.name’
                if (is_string($parameter)) {
                    return call_user_func_array($method, [$parameter]);
                }
            }
        }

        return $this->app['url']->current();
    }

    /**
     * 表单方法
     * @param  array $options
     * @return string
     */
    protected function formEnctype(&$options)
    {
        $keys = ['files', 'enctype'];

        if ($attributes = Arr::only($options, $keys)) {
            Arr::forget($options, $keys);
            return 'multipart/form-data';
        }

        return null;
    }

    /**
     * 检查类型是否存在
     * 
     * @param  string  $type 类型名称
     * @return boolean
     */
    protected function hasType($type)
    {
        return $this->hasMacro($type) || method_exists($this, $type) || in_array($type, Arr::flatten($this->types));
    }

    /**
     * 依次查找类型，直到找到，找不到返回text
     * @param  array $types 类型
     * @return string
     */
    protected function findType(...$types)
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
     * @param  array  $attributes 字段属性
     * @return html
     */
    public function field(array $attributes = [])
    {
        // 键名小写
        $attributes = array_change_key_case($attributes);

        // 获取类型
        $type = Arr::get($attributes, 'type');
        $type = $this->findType($type);

        return $this->$type($attributes);
    }


    /**
     * input 类型，<input type="……">
     * @param  array  $attributes 属性
     * @return string
     */
    protected function input(array $attributes)
    {
        $type  = Arr::pull($attributes, 'type', 'text');
        $class = Arr::pull($attributes, 'class', null);
        $class = $class ? $this->fieldDefaultClass . " {$class}" : $this->fieldDefaultClass;

        $id    = $this->getId($attributes);
        $value = $this->getValue($attributes);

        $attributes = array_merge($attributes, compact('type', 'id', 'value', 'class'));

        return $this->toHtmlString('<input ' . $this->attributes($attributes) . '>');
    }

    /**
     * token 字段
     * @return string
     */
    public function token()
    {
        $token = $this->app['session.store']->token();
        $token = !empty($token) ? $token : $this->app['session']->token();

        return $this->hidden(['name' => '_token', 'value' => $token]);
    }

    /**
     * textarea 类型，<textarea></textarea>
     * @param  array  $attributes 属性
     * @return string
     */
    public function textarea(array $attributes)
    {
        $type  = Arr::pull($attributes, 'type');

        $id    = $this->getId($attributes);
        $value = $this->getValue($attributes);
        $cols  = Arr::pull($attributes, 'cols', 50);
        $rows  = Arr::pull($attributes, 'rows', 10);
        $class = Arr::pull($attributes, 'class', null);
        $class = $class ? $this->fieldDefaultClass . " {$class}" : $this->fieldDefaultClass;

        $attributes = array_merge($attributes, compact('id', 'value', 'class', 'cols', 'rows'));

        return $this->toHtmlString('<textarea ' . $this->attributes($attributes) . '>' . $value . '</textarea>');
    }

    /**
     * input 类型，<button type="……"></button>
     * @param  array  $attributes 属性
     * @return string
     */
    public function button(array $attributes)
    {
        $type  = Arr::pull($attributes, 'type', 'button');
        $class = Arr::pull($attributes, 'class', null);
        $class = $class ? $this->fieldButtonClass[$type] . " {$class}" : $this->fieldButtonClass[$type];

        $attributes = array_merge($attributes, compact('type', 'class'));

        // 显示内容
        $value = Arr::pull($attributes, 'value', trans(Str::studly($type)));

        // 增加图标
        if ($icon = Arr::pull($attributes, 'icon', $this->fieldButtonIcon[$type])) {
            $value = '<i class="' . $icon . ' fa-fw"></i> ' . $value;
        }

        return $this->toHtmlString('<button ' . $this->attributes($attributes) . '>' . $value . '</button>');
    }

    /**
     * select 类型，<select></select>
     * @param  array  $attributes 属性
     * @return string
     */
    public function select(array $attributes)
    {
        // 样式
        $class    = Arr::pull($attributes, 'class', null);
        $class    = $class ? $this->fieldDefaultClass . " {$class}" : $this->fieldDefaultClass;

        // 是否为多选
        $multiple = Arr::pull($attributes, 'multiple') ? true : false;

        $attributes = array_merge($attributes, compact('class', 'multiple'));

        // 取出value作为选择的项
        $selected = $this->getValue($attributes, $multiple ? [] : null, true);

        $html = [];

        // 占位选项
        $placeholder = $this->getAttribute($attributes, 'placeholder', null, true);

        if ($placeholder) {
            $html[] = $this->convertSelectOption('', $placeholder, $selected, false);
        }

        // 取出选项
        $options = $this->getAttribute($attributes, 'options', [], true);

        foreach ($options as $value => $display) {
            if (is_array($display)) {
                $html[] = $this->convertSelectOptionGroup($value, $display, $selected, $multiple);
            } else {
                $html[] = $this->convertSelectOption($value, $display, $selected, $multiple);
            }
        }

        return $this->toHtmlString('<select ' . $this->attributes($attributes) . '>' . implode('', $html) . '</select>');
    }

    /**
     * 转换select的optgroup
     * @param  mixed $label    选项标签
     * @param  array $options 选项组
     * @param  mixed $selected 选择的项
     * @param  bool $multiple 是否多选
     * @return string
     */
    protected function convertSelectOptionGroup($label, $options, $selected, $multiple, $level = 0)
    {
        $space = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);

        $attributes = [
            'label' => $space . $label
        ];

        $html = [];

        foreach ($options as $value => $display) {
            if (is_array($display)) {
                $html[] = $this->convertSelectOptionGroup($value, $display, $selected, $multiple, $level + 1);
            } else {
                $html[] = $this->convertSelectOption($value, $display, $selected, $multiple);
            }
        }

        return $this->toHtmlString('<optgroup ' . $this->attributes($attributes) . '>' . implode('', $html) . '</optgroup>');
    }

    /**
     * 转换select的option
     * @param  mixed $value    选项值
     * @param  string $display 选项显示内容
     * @param  mixed $selected 选择的项
     * @param  bool $multiple 是否多选
     * @return string
     */
    protected function convertSelectOption($value, $display, $selected, $multiple)
    {
        if ($multiple) {
            $isSelected = in_array($value, Arr::wrap($selected));
        } else if (is_int($value) && is_bool($selected)) {
            $isSelected = (bool) $value === $selected;
        } else {
            $isSelected = (string) $value === (string) $selected;
        }

        $attributes = [
            'selected' => $isSelected,
            'value'    => $value,
        ];

        return $this->toHtmlString('<option ' . $this->attributes($attributes) . '>' . e($display, false) . '</option>');
    }

    /**
     * checkbox 类型，<input type="checkbox">
     * @param  array  $attributes 属性
     * @return string
     */
    public function checkbox(array $attributes)
    {
        $attributes['type'] = 'checkbox';
        return $this->checkable($attributes);
    }

    /**
     * radio 类型，<input type="radio">
     * @param  array  $attributes 属性
     * @return string
     */
    public function radio(array $attributes)
    {
        $attributes['type'] = 'radio';
        return $this->checkable($attributes);
    }

    /**
     * checkbox 和 radio
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    protected function checkable(array $attributes)
    {
        // 取出传入的value
        $value = Arr::pull($attributes, 'value');

        // 传入的checked
        if (Arr::has($attributes, 'checked')) {
            $checked = $this->getAttribute($attributes, 'checked', false);
        } else if ($attributes['type'] == 'checkbox') {
            $checked = in_array($value, Arr::wrap($this->getValue($attributes))) ? true : false;
        } else {
            $checked = ($this->getValue($attributes) == $value) ? true : false;
        }

        // id
        $id = Arr::pull($attributes, 'id');

        if (empty($id)) {
            $id = $this->getName($attributes) . '-' . $value;
        }

        // 重新拼装
        $attributes = array_merge($attributes, compact('value', 'checked', 'id'));

        // 传入label
        if ($label = Arr::pull($attributes, 'label')) {
            $label = '<label for="' . $this->getId($attributes) . '">' . $label . '</label>';
        }

        return $this->input($attributes) . $label;
    }


    /**
     * 添加class
     * @param  array   $attributes 属性
     * @param  mixed   $add 添加的class
     * @param  boolean $prepend 是否前置
     * @return array
     */
    protected function addClass(&$attributes, $add, $prepend = false)
    {
        $class = Arr::get($attributes, 'class');
        $class = is_string($class) ? explode(' ', $class) : $class;

        // 添加的class是前置，还是后置
        if ($prepend) {
            $class = array_merge(Arr::wrap($add), Arr::wrap($class));
        } else {
            $class = array_merge(Arr::wrap($class), Arr::wrap($add));
        }

        $class = array_values(array_unique($class));
        $attributes['class'] = $class;
        return $class;
    }

    /**
     * 删除 class
     * @param  array $attributes 属性
     * @return null
     */
    protected function removeClass(&$attributes)
    {
        $attributes['class'] = null;
        return null;
    }

    /**
     * 从属性中取出标签项
     * @param  array  &$attributes 属性数组
     * @param  array|string  $key  属性键名或者键名数组
     * @param  mixed  $default     默认值
     * @param  boolean $pull       是否从属性数组中删除
     * @return mixed
     */
    protected function getAttribute(&$attributes, $key, $default = null, $pull = true)
    {
        // key可以是多个
        $keys = Arr::wrap($key);

        // 取出第一个存在的标签值
        $value = null;

        foreach ($keys as $key) {
            if (array_key_exists($key, $attributes)) {
                $value = $attributes[$key];
                break;
            }
        }

        // 从原数组中删除
        if ($pull) {
            Arr::forget($attributes, $keys);
        }

        // 属性存在，
        if (!is_null($value)) {

            // 默认值如果为数组，则value也必须是数组，深度合并默认值默认值和value
            if (is_array($default)) {
                $value = is_array($value) ? $value : [];
                foreach (Arr::dot($default) as $k => $v) {
                    Arr::set($value, $k, $v);
                }
            }

            // 默认值是boolean，转换value为boolean
            if (is_bool($default)) {
                $value = (bool) $value;
            }

            return $value;
        }

        return $default;
    }

    /**
     * 从属性中取出name
     * @param  array $attributes 属性数组
     * @param  string $default 默认值
     * @param  boolean $pull 是否从属性数组中删除，默认不删除
     * @return string
     */
    protected function getName(&$attributes, $default = null, $pull = false)
    {
        return $this->getAttribute($attributes, 'name', $default, $pull);
    }

    /**
     * 从属性中获取id
     * @param  array $attributes 属性数组
     * @param  string $default 默认值
     * @param  boolean $pull 是否从属性数组中删除，默认不删除
     * @return string
     */
    protected function getId(&$attributes, $default = null, $pull = false)
    {
        // 如果有id，直接获取id，否则获取name    
        $id = $this->getAttribute($attributes, ['id', 'name'], $default, $pull);

        // 格式化从name获取的id
        return str_replace(['.', '[]', '[', ']'], ['-', '', '-', ''], $id);
    }

    /**
     * 从属性或者绑定的数组中获取值
     * @param  array $attributes 属性数组
     * @param  string $default 默认值
     * @param  boolean $pull 是否从属性数组中删除，默认不删除
     * @return mixed
     */
    protected function getValue(&$attributes, $default = null, $pull = false)
    {
        // 如果属性中存在value，直接返回
        if (Arr::has($attributes, 'value')) {
            return $pull ? Arr::pull($attributes, 'value') : Arr::get($attributes, 'value');
        }

        if ($name = Arr::get($attributes, 'name')) {

            // 将数组名称转为点语法 test[aaa] 转为 test.aaa 
            $key = $this->transformNameDot($name);

            // 从闪存数据中取值
            $old = $this->app['request']->old($key);

            if (!is_null($old)) {
                return $old;
            }

            // 从绑定数据中取值
            return data_get($this->bind, $key, $default);
        }

        return $default;
    }

    /**
     * 将数组名称转为点语法 test[aaa] 转为 test.aaa 
     * @param  string $name 字段名
     * @return string
     */
    protected function transformNameDot($name)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
    }

    /**
     * 属性数组转化为属性字符串
     *
     * @param array $attributes
     * @return string
     */
    protected function attributes($attributes)
    {
        return $this->app['html']->attributes($attributes);
    }

    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Illuminate\Contracts\View\View|mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        // 调用input字段
        if (in_array($method, $this->types['input'])) {
            $parameters[0]['type'] = $method;
            return call_user_func_array([$this, 'input'], $parameters);
        }

        // 调用button字段
        if (in_array($method, $this->types['button'])) {
            $parameters[0]['type'] = $method;
            return call_user_func_array([$this, 'button'], $parameters);
        }

        static::throwBadMethodCallException($method);
    }
}
