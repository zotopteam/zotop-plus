<?php

namespace Modules\Core\View\Components;

use App\Support\Facades\Html;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class SideBar extends Component
{
    /**
     * 模块的data文件或者数组
     *
     * @var mixed
     */
    public $data;

    /**
     * 头部内容
     *
     * @var string
     */
    public $header;

    /**
     * 尾部内容
     *
     * @var string
     */
    public $footer;

    /**
     * 样式
     *
     * @var string
     */
    public $class;

    /**
     * 当前的活动项
     *
     * @var string
     */
    public $active;

    /**
     * 视图文件
     *
     * @var array
     */
    public $view;

    /**
     * Create a new component instance.
     *
     * @param $data
     * @param null $header
     * @param null $footer
     * @param null $class
     * @param null $active
     * @param string $view
     */
    public function __construct(
        $data,
        $header = null,
        $footer = null,
        $class = null,
        $active = null,
        $view = 'core::components.sidebar'
    )
    {
        $this->data = $data;
        $this->header = $header;
        $this->footer = $footer;
        $this->view = $view;
        $this->class = $class ? 'side ' . $class : 'side';
        $this->active = $active;
    }

    /**
     * 获取导航数组
     *
     * @return array
     */
    protected function getNavigations()
    {
        $data = call_user_func_array(['Module', 'data'], Arr::wrap($this->data));

        $navigations = [];

        foreach ($data as $key => $attrs) {

            $item = [];
            $item['text'] = Arr::pull($attrs, 'text');
            $item['icon'] = Arr::pull($attrs, 'icon');
            $item['href'] = Arr::pull($attrs, 'href');
            $item['class'] = Arr::pull($attrs, 'class');

            if ($key == $this->active || Arr::pull($attrs, 'active')) {
                $item['class'] = empty($item['class']) ? 'active' : $item['class'] . ' active';
            }

            $item['attrs'] = Html::attributes($attrs);

            $navigations[] = $item;
        }

        return $navigations;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view($this->view)->with('navigations', $this->getNavigations());
    }
}
