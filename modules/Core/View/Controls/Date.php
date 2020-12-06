<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class Date extends Control
{
    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $icon = null,
        $options = []
    )
    {
        $this->icon = $icon;
        $this->options = Arr::wrap($options);
    }

    /**
     * laydate options
     *
     * @param array $options
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function options(array $options)
    {
        $default = [
            'elem'     => '#' . $this->attributes->get('id'),
            'type'     => $this->type,
            'value'    => $this->attributes->get('value'),
            'position' => 'absolute',
            'format'   => 'yyyy-MM-dd',
            'lang'     => App::getLocale(), //TODO:语言问题由于laydate只支持cn和en，需要优化
            'min'      => '1900-1-1',
            'max'      => '2099-12-31',
            'range'    => false,
            'theme'    => '#0072c6',
            'btns'     => ['clear', 'now', 'confirm'],
            'trigger'  => 'click',
        ];

        // 标签上取出的属性
        $attributes = $this->attributes->pull(array_keys($default));

        // 设置属性
        $options = attribute($options)->merge($default, false)->merge($attributes->toArray());

        return $options->toArray();
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        $this->options = $this->options($this->options);
        
        return $this->view('core::controls.date');
    }
}
