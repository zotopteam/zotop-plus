<?php

namespace Modules\Navbar\View\Components;

use Illuminate\View\Component;
use Modules\Navbar\Models\Navbar;

class NavbarComponent extends Component
{
    /**
     * 调用标识，如果为空则调用默认导航条
     *
     * @var
     */
    public $slug;

    /**
     * 视图
     *
     * @var
     */
    public $view;

    /**
     * 获取导航条数据
     *
     * @return array|\Illuminate\Database\Concerns\BuildsQueries[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @author Chen Lei
     * @date 2021-02-03
     */
    private function getNavbar()
    {
        return Navbar::with('item.children')
            ->when($this->slug, function ($query, $slug) {
                return $query->where('slug', $slug);
            }, function ($query) {
                return $query->where('id', 0);
            })
            ->get();
    }

    /**
     * Create a new component instance.
     *
     * @param string|null $slug
     * @param string|null $view
     */
    public function __construct(
        string $slug = null,
        string $view = null
    )
    {
        $this->slug = $slug;
        $this->view = $view;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('navbar::components.navbar');
    }
}
