<?php

namespace Modules\Translator\Support;

use Config;

class Translator
{
	protected $engine;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($engine=null)
    {
        $this->engine = $engine ?? \Config::get('translator.engine');;
    }

    /**
     * 翻译
     * 
     * @param  string $text 待翻译文本
     * @param  string $from 源语言
     * @param  string $to   目标语言
     * @return string
     */
    public function translate($text, $from=null, $to=null)
    {
        $from   = $from ?? \Config::get('translator.from');
        $to     = $to ?? \Config::get('translator.to');
                
        return $this->getEngine($this->engine)->translate($text, $from, $to);
    }

    /**
     * 获取翻译引擎
     * 
     * @param  string $engine 引擎
     * @return object
     */
    public function getEngine($engine)
    {
        $engine = ucfirst($engine);
        $engine = __NAMESPACE__.'\\'.$engine;

        return new $engine();
    }
}
