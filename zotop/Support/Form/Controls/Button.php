<?php

namespace Zotop\Support\Form\Controls;

use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Button extends Control
{
    /**
     * 按钮默认样式
     *
     * @var array
     */
    public static $defaultClass = [
        'submit' => 'btn btn-primary',
        'save'   => 'btn btn-primary',
        'button' => 'btn btn-secondary',
        'reset'  => 'btn btn-light',
    ];

    /**
     * 按钮默认图标
     *
     * @var array
     */
    public static $defaultIcon = [
        'submit' => 'fa fa-save',
        'save'   => 'fa fa-save',
        'button' => 'fa fa-check-circle',
        'reset'  => 'fa fa-undo',
    ];

    /**
     * 按钮默认文字
     *
     * @var array
     */
    public static $defaultText = [
        'submit' => 'master.submit',
        'save'   => 'master.save',
        'button' => 'master.button',
        'reset'  => 'master.reset',
    ];

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value = null,
        $icon = true
    )
    {
        $this->value = $value;
        $this->icon = $icon;
    }

    /**
     * 获取button的默认值
     *
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getValue()
    {
        $text = Arr::get(static::$defaultText, $this->type, Str::studly($this->type));

        return $this->value ?? trans($text);
    }

    /**
     * 获取button默认样式
     *
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function getDefaultClass()
    {
        return Arr::get(static::$defaultClass, $this->type);
    }

    /**
     * 获取button默认图标
     *
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function getIcon()
    {
        // 如果传入的值为 空、false或者null，不显示图标
        if (empty($this->icon)) {
            return '';
        }

        // 如果传入的值为真或者default，显示默认图标
        if ($this->icon === true || $this->icon == 'default') {
            $this->icon = Arr::get(static::$defaultIcon, $this->type);
        }

        return '<i class="' . $this->icon . ' fa-fw"></i> ';
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function render()
    {
        $this->attributes->merge([
            'type' => $this->type,
        ]);

        return $this->toHtmlString(
            '<button ' . $this->attributes . ' >' . $this->getIcon() . $this->getValue() . '</button>'
        );
    }
}
