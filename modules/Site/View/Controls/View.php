<?php

namespace Modules\Site\View\Controls;

use Zotop\View\Form\Control;

class View extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $view = null,
        $theme = null,
        $module = null,
        $select = null,
        $icon = null,
        $button = null
    )
    {
        $this->view = $view;
        $this->theme = $theme ?? config('site.theme');
        $this->module = $module ?? app('current.module');
        $this->select = $select;
        $this->icon = $icon;
        $this->button = $button ?? trans('site::field.view.select');
    }

    /**
     * Boot the control
     */
    public function bootView()
    {
        $this->id = $this->attributes->get('id');

        $this->select = $this->select ?? route('site.view.select', [
                'theme'  => $this->theme,
                'module' => $this->module,
            ]);
    }

    /**
     * Render the control
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view($this->view ?? 'site::controls.view');
    }
}
