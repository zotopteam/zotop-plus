<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Modules\Facades\Module;
use App\Modules\Routing\AdminController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FormController extends Controller
{
    /**
     * 首页
     *
     * @return \App\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->success(trans('master.saved'));
        }

        $this->title = trans('developer::form.form');

        $this->attributes = Module::data('developer::form.attributes');

        $this->bind = [
            'title'   => 'title',
            'content' => 'content',
        ];

        return $this->view();
    }

    /**
     * 控件示例
     *
     * @param \Illuminate\Http\Request $request
     * @param string $control
     * @return \Illuminate\Contracts\View\View
     * @throws \App\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Chen Lei
     * @date 2020-12-26
     */
    public function control(Request $request, $control = 'text')
    {
        $this->control = $control;
        $this->controls = Module::data('developer::form.controls');
        $this->title = Arr::get($this->controls, "{$control}.text");

        // 标签
        $this->attributes = Arr::get($this->controls, "{$control}.attributes");
        $this->attributes = collect($this->attributes)->transform(function ($item) {
            return is_array($item) ? $item : Module::data($item);
        })->mapWithKeys(function ($item) {
            return $item;
        })->transform(function ($item, $key) {
            if ($key == 'type' && !isset($item['value'])) {
                $item['value'] = $this->control;
            }
            return $item;
        })->filter()->toArray();

        // 示例
        $this->examples = Arr::get($this->controls, "{$control}.examples");
        $this->examples = collect($this->examples)->transform(function ($item) {
            return is_array($item) ? $item : Module::data($item, ['type' => $this->control]);
        })->flatten(1)->transform(function ($item) {
            return attribute($item);
        })->toArray();

        return $this->view();
    }
}
