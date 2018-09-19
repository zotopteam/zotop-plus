<?php
namespace Modules\Tinymce\Hook;

use Module;


class Listener
{
    /**
     * 监听编辑器属性
     * 
     * @param  array $options 属性数组
     * @return array
     */    
    public function options($options)
    {
        $mode = $options['mode'];

        if (in_array($options['mode'], ['full','standard','simple'])) {
            $options = array_merge(
                $options,
                Module::data('tinymce::tinymce.'.$options['mode'])
            );
        }

        debug($options);

        return $options;
    }    
}
