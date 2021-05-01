<?php

namespace Zotop\HttpClient;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Throwable;

/**
 * Class HttpClientException
 *
 */
class HttpClientException extends Exception
{
    /**
     * 发生异常的HttpClient实例
     *
     * @var
     */
    public $httpClient;

    /**
     * The previous throwable used for the exception chaining.
     *
     * @var Throwable
     */
    public $previous;


    /**
     * Create a new exception instance.
     *
     * @param HttpClientAbstract $httpClient 发生异常的HttpClient实例
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct($message, $code, HttpClientAbstract $httpClient, Throwable $previous = null)
    {
        $this->httpClient = $httpClient;
        $this->previous = $previous;

        // 处理程序异常
        if ($this->previous instanceof ConnectionException) { // 连接失败的情况
            $httpClient->log([
                'code'    => $this->previous->getCode(),
                'message' => $this->previous->getMessage(),
            ]);
        } else if ($this->previous instanceof RequestException) { // 响应报错的情况
            $httpClient->log(
                !empty($this->previous->response->json())
                    ? $this->previous->response->json()
                    : ['body' => $this->previous->response->body()]
            );
        } else if ($this->previous) { // 其他异常情况
            $httpClient->log([
                'code'    => $this->previous->getCode(),
                'message' => $this->previous->getMessage(),
            ]);
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * 得到异常信息
     *
     * @return array
     */
    public function getLog()
    {
        return $this->httpClient->log();
    }
}
