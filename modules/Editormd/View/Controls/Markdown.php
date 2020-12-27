<?php

namespace Modules\Editormd\View\Controls;

use App\Modules\Facades\Module;
use App\Support\Form\Control;
use Illuminate\Support\Arr;

class Markdown extends Control
{
    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $name = null,
        $value = null,
        $options = []
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->options = Arr::wrap($options);
    }

    /**
     * options
     *
     * @param array $options
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function options(array $options)
    {
        $default = [
            'width'              => '100%',
            'height'             => '500',
            'placeholder'        => 'content……',
            'autoHeight'         => false,
            'toolbar'            => true,
            'codeFold'           => true,
            'saveHTMLToTextarea' => true,
            'htmlDecode'         => 'style,script,iframe|on*',
            'theme'              => 'default',
            'previewTheme'       => 'default',
            'editorTheme'        => 'default',
            'path'               => Module::asset('editormd:editormd/lib', false) . '/',
        ];

        // 标签上取出的属性
        $attributes = $this->attributes->pull(array_keys($default));

        // 设置属性
        $options = attribute($options)->merge($default, false)->merge($attributes->toArray());

        return $options->toArray();
    }

    /**
     * 启动 Markdown
     *
     * @author Chen Lei
     * @date 2020-12-15
     */
    public function bootMarkdown()
    {
        $this->options = $this->options($this->options);
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('editormd::controls.markdown');
    }
}
