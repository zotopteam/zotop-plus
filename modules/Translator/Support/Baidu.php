<?php

namespace Modules\Translator\Support;

use Config;
use Filter;

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
        $from = $this->language($from);
        $to   = $this->language($to);

        $salt = rand(10000,99999);
        $sign = md5($this->appid . $text . $salt . $this->secretkey);
        $text = rawurlencode($text);
        $url  = "{$this->url}?q={$text}&from={$from}&to={$to}&appid={$this->appid}&salt={$salt}&sign={$sign}";
        debug($url);

        // 获取翻译结果
        $translate = $this->getResult($url);
        $translate = json_decode($translate, true);
        debug($translate);

        // 返回翻译结果
        if (is_array($translate)) {

            if (isset($translate['trans_result'])) {
                return ucfirst($translate['trans_result'][0]['dst']);
            }

            if (isset($translate['error_code']) && isset($translate['error_msg'])) {
                abort(403, 'Unable to translate, Baidu error code: '.$translate['error_code'].' error reason: '.$translate['error_msg']);
            }
        }

        return null;
    }

    /*
     * 获取翻译结果
     */
    private function getResult($url)
    {
        try {
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
        } catch (\Exception $e) {
            abort(503, 'Unable to connect to translation server: '.$this->url);
        }
    }

    private function language($lang)
    {
        $langs = Filter::fire('translator.baidu.language.transform', [
            'zh-Hans' => 'zh',
            'zh-Hant' => 'cht',
        ]);

        return isset($langs[$lang]) ? $langs[$lang] : $lang;
    }   
}
