<?php

namespace Modules\Block\View\Components;

use Illuminate\View\Component;
use Modules\Block\Models\Block;

class BlockComponent extends Component
{
    /**
     * 模块标识
     *
     * @var integer
     */
    public $id;

    /**
     * 模块标识
     *
     * @var string
     */
    public $slug;

    /**
     * 模板
     *
     * @var string
     */
    public $view;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id   = null,
        $slug = null,
        $view = null
    ) {
        $this->id   = $id;
        $this->slug = $slug;
        $this->view = $view;
    }

    /**
     * 获取区块数据
     *
     * @return \Modules\Block\Models\Block
     */
    private function getBlock()
    {
        $block = null;

        if ($this->id || $this->slug) {
            $block = $this->id ? Block::where('id', $this->id)->first() : Block::where('slug', $this->slug)->first();
        }

        return $block;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $view = 'block::components.404';

        // 模块设置的模板数据
        if ($block = $this->getBlock()) {
            $view = $this->view ?? $block->view;
        }

        return view($view)->with('block', $block);
    }
}
