<?php

namespace Zotop\Hook\Tests;

class TestFilter
{
    /**
     * 滤器
     *
     * @param mixed $value
     * @param string $args
     * @return mixed
     * @author Chen Lei
     * @date 2021-03-18
     */
    public function handle($value, string $args = null)
    {
        return $value . ':class';
    }
}
