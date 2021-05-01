<?php

namespace Zotop\Hook\Tests;

class TestHook
{
    /**
     * 测试动作
     *
     * @param $arg1
     * @author Chen Lei
     * @date 2021-03-18
     */
    public function myAction($arg1)
    {
        echo $arg1 . ':method';
    }

    /**
     * 测试滤器
     *
     * @param $value
     * @return string
     * @author Chen Lei
     * @date 2021-03-18
     */
    public function myFilter($value)
    {
        return $value . ':method';
    }
}
