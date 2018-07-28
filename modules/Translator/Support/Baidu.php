<?php

namespace Modules\Translator\Support;

use Config;

class Baidu implements EngineInterface
{
    protected $url;
    protected $appid;
    protected $secretkey;

    /**
     * 初始化
     */
    public function __construct() {
        $this->url       = 'https://api.fanyi.baidu.com/api/trans/vip/translate';
        $this->appid     = Config::get('translator.baidu.appid');
        $this->secretkey = Config::get('translator.baidu.secretkey');
    }
	
    /**
     * 翻译
     * 
     * @param  string $text 待翻译文本
     * @param  string $from 源语言
     * @param  string $to   目标语言
     * @return string
     */
    public function translate($text, $from, $to)
    {
        $salt = rand(10000,99999);
        $sign = md5($this->appid . $text . $salt . $this->secretkey);
        $text = rawurlencode($text);
        $url  = "{$this->url}?q={$text}&from={$from}&to={$to}&appid={$this->appid}&salt={$salt}&sign={$sign}";

        // 获取翻译结果
        $translate = $this->getResult($url);
        $translate = json_decode($translate, true);
        debug($translate);

        // 返回翻译结果
        if (is_array($translate) && isset($translate['trans_result'])) {
            $translate = $translate['trans_result'][0]['dst'];
        } else {
            $translate = '';
        }

        return $translate;
    }

    /*
     * 获取翻译结果
     */
    private function getResult($url)
    {
        if (function_exists('file_get_contents')) {
            $result = file_get_contents($url);
        } else {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        return $result;
    }    
}
