<?php

namespace Modules\Tinymce\View\Controls;

use Zotop\Hook\Facades\Filter;
use Zotop\View\Form\Control;

class Editor extends Control
{

    /**
     * Create a new control instance.
     *
     * @param string|null $id
     * @param string|null $name
     * @param string|null $value
     * @param string|null $class
     * @param integer|null $rows
     * @param string|array|null $options
     * @param string|null $view
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = null,
        $class = null,
        $rows = null,
        $options = null,
        $view = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->class = $class;
        $this->rows = $rows;
        $this->options = $options;
        $this->view = $view;
    }

    /**
     * Boot the control
     *
     * @return void
     * @throws \Exception
     */
    public function bootEditor()
    {
        // 属性值
        $this->options = Filter::fire('tinymce.editor.options', $this->options, $this->originalAttributes->toArray());

        // 标签自定义的属性值
        // 'menubar', 'toolbar', 'plugins', 'width', 'height', 'language', 'theme', 'skin', 'resize', 'placeholder'
        $this->options = array_merge($this->options, $this->attributes->toArray());

        // 如果定义了rows，转换为height
        if ($rows = intval($this->rows)) {
            $this->options = array_merge($this->options, [
                'height' => $rows * 35,
            ]);
        }
    }

    /**
     * Render the control
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view($this->view ?? 'tinymce::controls.editor');
    }
}
