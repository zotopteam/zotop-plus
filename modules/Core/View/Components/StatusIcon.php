<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;

class StatusIcon extends Component
{

    /**
     * 状态
     *
     * @var bool|integer
     */
    public $status;

    /**
     * 样式
     *
     * @var string
     */
    public $class;

    /**
     * Create a new component instance.
     *
     * @param $status
     * @param null $class
     */
    public function __construct(
        $status,
        $class = null
    )
    {
        $this->status = (bool)$status;
        $this->class = $class;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if ($this->status) {
            return '<i class="fa fa-fw fa-check-circle text-success ' . $this->class . '"></i>';
        }

        return '<i class="fa fa-fw fa-times-circle text-danger ' . $this->class . '"></i>';
    }
}
