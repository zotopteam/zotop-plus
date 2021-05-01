<?php

namespace Modules\Core\View\Controls;

use Zotop\Support\Attribute;
use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;

class RadioCards extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value = null,
        $options = null,
        $name = null,
        $column = 0,
        $class = 'radiocards-default'
    )
    {
        $this->name = $name;
        $this->column = $column;
        $this->class = $class;
        $this->options = Arr::wrap($options);
        $this->value = $this->getValue($value);
    }

    /**
     * 取得value值，如果没有选择值，选择options的第一个
     *
     * @param mixed $value
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getValue($value)
    {
        if (is_null($value)) {
            $value = array_keys($this->options);
            $value = reset($value);
        }

        return $value;
    }

    /**
     * 选项控件
     *
     * @param mixed $key
     * @return \Zotop\Support\Attribute
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function radio($key)
    {
        return new Attribute([
            'type'    => 'radio',
            'name'    => $this->name,
            'id'      => $this->name . '-' . $key,
            'value'   => $key,
            'checked' => ($key == $this->value),
        ]);
    }

    /**
     * 高级卡片模式
     *
     * @param $value $value=数组，高级模式，显示带图片、标题、描述和提示的卡片
     *               $value=字符，简单模式，显示为文字卡片模式
     * @return false|\Zotop\Support\Attribute
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function advanced($value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new Attribute($value);
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.radio-cards');
    }
}
