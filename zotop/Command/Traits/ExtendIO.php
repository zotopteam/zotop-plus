<?php

namespace Zotop\Command\Traits;


trait ExtendIO
{
    /**
     * 询问并校验答案，类似于ask但是如果回调校验返回false，会继续询问
     *
     * @param $question
     * @param callable $callback
     * @return mixed
     * @author Chen Lei
     * @date 2021-04-28
     */
    public function answer($question, callable $callback)
    {
        $answer = $this->ask($question);

        if (!$callback($answer)) {
            $this->answer($question, $callback);
        }

        return $answer;
    }

    /**
     * 选择，类似choice，但是会返回选项数组的index key
     *
     * @param $question
     * @param array $choices
     * @param string|null $default
     * @param mixed|null $attempts
     * @param bool $multiple
     * @return false|int|string
     * @author Chen Lei
     * @date 2021-04-28
     */
    public function select($question, array $choices, $default = null, $attempts = null, $multiple = false)
    {
        $choice = $this->choice($question, array_values($choices), $default, $attempts, $multiple);

        return array_search($choice, $choices);
    }
}
