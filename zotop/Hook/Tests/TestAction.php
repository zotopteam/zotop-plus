<?php

namespace Zotop\Hook\Tests;

class TestAction
{
    /**
     * 动作
     *
     * @param mixed $arg1
     * @param mixed $arg2
     * @return mixed
     * @author Chen Lei
     * @date 2021-03-18
     */
    public function handle($arg1, $arg2)
    {
        echo $arg1 . ':class';
    }
}
