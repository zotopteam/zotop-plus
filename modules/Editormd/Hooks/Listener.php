<?php

namespace Modules\Editormd\Hooks;

class Listener
{
    /**
     * Hook the start
     *
     * @param array $start
     * @return array
     */
    public function start($start)
    {
        return $start;
    }

    /**
     * Hook the navbar
     *
     * @param array $navbar
     * @return array
     */
    public function navbar($navbar)
    {
        return $navbar;
    }

    /**
     * 扩展表单助手
     *
     * @param array $data
     * @param array $args
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-27
     */
    public function controls(array $controls, array $args)
    {
        $controls['code'] = [
            'text'       => trans('editormd::form.control.code'),
            'href'       => route('developer.form.control', ['control' => 'code']),
            'icon'       => 'fa fa-code',
            'examples'   => [
                'developer::form.control.examples.standard',
                'editormd::form.control.examples.code',
            ],
            'attributes' => [
                'developer::form.control.attributes.standard',
                'developer::form.control.attributes.placeholder',
                'editormd::form.control.attributes.code',
            ],
        ];

        $controls['markdown'] = [
            'text'       => trans('editormd::form.control.markdown'),
            'href'       => route('developer.form.control', ['control' => 'markdown']),
            'icon'       => 'fab fa-markdown',
            'examples'   => 'editormd::form.control.examples.markdown',
            'attributes' => [
                'developer::form.control.attributes.standard',
                'developer::form.control.attributes.placeholder',
                'editormd::form.control.attributes.markdown',
            ],
        ];

        return $controls;
    }
}
