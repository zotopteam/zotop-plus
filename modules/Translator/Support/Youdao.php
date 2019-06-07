<?php

namespace Modules\Translator\Support;

use Config;
use Filter;

class Youdao implements EngineInterface
{
    protected $url;
    protected $appid;
    protected $secretkey;

    /**
     * 初始化
     */
    public function __construct() {
        $this->url       = 'http://openapi.youdao.com/api';
        $this->appid     = Config::get('translator.youdao.appid');
        $this->secretkey = Config::get('translator.youdao.secretkey');
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

        debug($text, $from, $to);

        $salt = $this->create_guid();
        $curtime = strtotime("now");
        $sign = hash("sha256", $this->appid . $this->truncate($text) . $salt . $curtime . $this->secretkey);
        
        $args = array(
            'q'        => $text,
            'appKey'   => $this->appid,
            'salt'     => $salt,
            'from'     => $from,
            'to'       => $to,
            'signType' => 'v3',
            'curtime'  => $curtime,
            'sign'     => $sign,
        );

        $translate = $this->call($this->url, $args);
        $translate = json_decode($translate, true);

        debug($translate);

        // 结果正确
        if (is_array($translate) && isset($translate['errorCode'])) {
            if ($translate['errorCode'] == 0 && isset($translate['translation'])) {
                return $translate['translation'][0];
            } else {
                abort(403, 'Unable to translate, Youdao error code: '.$translate['errorCode']. ', error Reason: http://ai.youdao.com/docs/doc-trans-api.s#p08');
            }            
        }


        return null;
    }

    private function call($url, $args)
    {
        try {
            $data = $this->convert($args);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2000);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\Exception $e) {
            abort(503, 'Unable to connect to translation server: '.$this->url);
        }                   
    }

    private function language($lang)
    {
        if ($lang == 'zh-Hant') {
            abort(403, 'Unable to translate, Youdao does not support '.$lang);
        }

        // 有道不支持翻译我繁体中文
        $langs = Filter::fire('translator.youdao.language.transform', [
            'zh-Hans' => 'zh-CHS',
        ]);

        return isset($langs[$lang]) ? $langs[$lang] : $lang;
    }

    private function convert(&$args)
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key=>$val) {
                if (is_array($val)) {
                    foreach ($val as $k=>$v) {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                } else {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }    

    // uuid generator
    private function create_guid()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);
        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);
        return $guid;
    }

    function create_guid_section($characters)
    {
        $return = "";
        for($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0,15));
        }
        return $return;
    }

    private function truncate($q)
    {
        // 网易官方PHP demo 此处为strlen和substr，但是当utf-8且语言为中文时，翻译较长中文会导致报202验证签名失败，因此改为mb函数
        $len = mb_strlen($q);
        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }

    private function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }         
}
