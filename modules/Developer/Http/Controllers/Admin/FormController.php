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
    public function index()
    {
        $this->title = trans('developer::form.title');

        // ,'button','submit','save','reset','select','radio','checkbox','view','content-title','content-summary','content-models','translate','slug','content-slug','tinymce','radiocards','radio-cards','radiogroup','radio-group','radios','bool','enable','checkboxgroup','checkbox-group','checkboxes','year','toggle','editor','code','markdown','icon','upload-image','upload-document','upload-archive','upload-video','upload-audio','upload','gallery'
        //$this->controls = Form::controls();
        // dd("'" . implode("','", array_keys($this->controls)) . "'");

        return $this->view();
    }

    /**
     * 控件组
     *
     * @param \Illuminate\Http\Request $request
     * @param string $group
     * @return \Illuminate\Contracts\View\View
     * @throws \App\Modules\Exceptions\ModuleNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Chen Lei
     * @date 2020-12-23
     */
    public function group(Request $request, $group = 'common')
    {
        $this->group = $group;
        $this->groups = Module::data('developer::form');
        $this->title = Arr::get($this->groups, "{$group}.text");
        $this->include = Arr::get($this->groups, "{$group}.view");
        $this->controls = Arr::get($this->groups, "{$group}.controls");

        $this->controls = collect($this->controls)->transform(function ($attributes, $control) {
            return array_merge([
                'type' => $control,
                'name' => $control,
            ], $attributes);
        });
        
        return $this->view();
    }
}
