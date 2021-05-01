<?php

namespace Zotop\HttpClient;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

/**
 * Class HttpClientAbstract
 *
 * 发送数据给第三方接口，并得到返回结果
 *
 * @method get(string $url = null, array $query = [])
 * @method patch(string $url = null, array $data = [])
 * @method post(string $url = null, array $data = [])
 * @method put(string $url = null, array $data = [])
 * @method delete(string $url = null, array $data = [])
 * @method head(string $url = null, array $query = [])
 */
abstract class HttpClientAbstract
{
    /**
     * http
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $http;

    /**
     * 接口基础地址
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * 当前接口地址，不含基础地址，需要在接口中重载
     *
     * @var string
     */
    protected $url = '';

    /**
     * http 发送方法，默认为get，可以在接口中重载
     *
     * @var string
     */
    protected $method = 'get';

    /**
     * 发送的头部数据
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 选项
     *
     * @var array
     */
    protected $options = [];

    /**
     * url中的参数
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * http 发送的数据
     *
     * @var array
     */
    protected $data = [];

    /**
     * 辅助的资源数据
     *
     * @var array
     */
    protected $resource = [];

    /**
     * 日志数据
     *
     * @var array
     */
    protected $log = [];

    /**
     * 尝试次数
     *
     * @var int
     */
    protected $retryTimes = 3;

    /**
     * 尝试延迟时间，单位毫秒
     *
     * @var int
     */
    protected $retryDelay = 100;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->http = Http::baseUrl($this->baseUrl);
    }

    /**
     * 获取实例，目前这里不是唯一实例，只是为了方便编码
     *
     * @author Chen Lei
     * @date 2021-04-28
     */
    public static function instance()
    {
        return new static();
    }

    /**
     * api地址
     *
     * @param string $url
     * @return $this
     * @author Chen Lei
     * @date 2021-04-28
     */
    public function url(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * api基本地址
     *
     * @param string $baseUrl
     * @return $this
     * @author Chen Lei
     * @date 2021-04-28
     */
    public function baseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * api基本地址
     *
     * @param string $method
     * @return $this
     * @author Chen Lei
     * @date 2021-04-28
     */
    public function method(string $method)
    {
        $this->method = $method;

        return $this;
    }


    /**
     * 重新尝试
     *
     * @param int $times
     * @param int $sleep
     * @return $this
     */
    public function retry(int $times, int $sleep = 0)
    {
        $this->retryTimes = $times;
        $this->retryDelay = $sleep;

        return $this;
    }

    /**
     *  http 发送的数据内容
     *
     * @param mixed $key
     * @param mixed $value
     * @return HttpClientAbstract
     */
    public function parameter($key, $value = null)
    {
        if (is_array($key)) {
            $parameters = $key;
        } else {
            $parameters = [$key => $value];
        }

        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     *  http 选项
     *
     * @param mixed $key
     * @param mixed $value
     * @return HttpClientAbstract
     */
    public function option($key, $value = null)
    {
        if (is_array($key)) {
            $options = $key;
        } else {
            $options = [$key => $value];
        }

        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     *  http 发送的数据内容
     *
     * @param mixed $key
     * @param mixed $value
     * @return HttpClientAbstract
     */
    public function data($key, $value = null)
    {
        if (is_array($key)) {
            $data = $key;
        } else {
            $data = [$key => $value];
        }

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     *  设置header
     *
     * @param mixed $key
     * @param mixed $value
     * @return HttpClientAbstract
     */
    public function header($key, $value = null)
    {
        if (is_array($key)) {
            $headers = $key;
        } else {
            $headers = [$key => $value];
        }

        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     *  辅助用的资源数据读写
     *
     * @param mixed $key 字符串 key为读取的键名，数组key为写入
     * @param mixed $default 默认值
     * @return mixed
     */
    public function resource($key, $default = null)
    {
        if (is_array($key)) {
            $this->resource = array_merge($this->resource, $key);
            return $this;
        }

        return Arr::get($this->resource, $key, $default);
    }


    /**
     * 发送http请求，并返回结果
     *
     * @param string|null $method
     * @param string|null $url
     * @param array $data
     * @return array|void 返回响应数据。默认情况下如果调用失败，则抛出HttpClientException异常。
     * @throws \App\Support\HttpClient\HttpClientException
     */
    public function send(string $method = null, string $url = null, array $data = [])
    {
        $this->method = $method ?? $this->method;
        $this->url = $url ?? $this->url;
        $this->data($data);

        // 发送数据之前
        $this->beforeSending();

        try {
            // 发送http请求
            $result = $this->sending();
        } catch (Throwable $th) {
            return $this->throw($th);
        }

        // 发送数据之后
        $this->afterSending($result);

        if ($this->isSuccess($result)) {
            return $this->success($result);
        }

        return $this->error($result);
    }

    /**
     * 发送请求
     *
     * @return mixed
     * @author Chen Lei
     * @date 2021-04-28
     */
    protected function sending()
    {
        return $this->http
            ->retry($this->retryTimes, $this->retryDelay)
            ->withOptions($this->options)
            ->withHeaders($this->headers)
            ->{$this->method}($this->url, $this->data)
            ->throw()
            ->json();
    }

    /**
     * 发送数据之前
     *
     * @return void
     */
    protected function beforeSending()
    {
        // 处理url中的参数
        collect($this->parameters)->map(function ($value, $key) {
            $this->url = Str::of($this->url)->replace("{{$key}}", $value);
        });
    }

    /**
     * 发送数据之后
     *
     * @param array $result
     */
    protected function afterSending(array $result)
    {
        // 记录日志
        $this->log($result);
    }

    /**
     * 判断请求是否返回了成功的数据
     *
     * @param array $result
     * @return bool
     */
    protected function isSuccess(array $result)
    {
        return isset($result['status']) && $result['status'];
    }

    /**
     * 处理http请求结果中返回的data数据
     *
     * @param mixed $result
     * @return array|null
     */
    protected function success($result)
    {
        return $result['data'] ?? null;
    }

    /**
     * 设置/获取日志内容
     *
     * @param null $data 日志数据
     * @return array
     */
    public function log($data = null)
    {
        if (is_null($data)) {
            return $this->log;
        }

        $this->log = [
            'url'     => $this->baseUrl . $this->url,
            'method'  => $this->method,
            'headers' => $this->headers,
            'data'    => $this->data,
            'result'  => $data,
        ];

        return $this->log;
    }

    /**
     * 处理错误的返回数据
     *
     * @param mixed $result
     * @return mixed|void
     * @throws \App\Support\HttpClient\HttpClientException
     */
    protected function error($result)
    {
        throw new HttpClientException($result['message'], $result['code'], $this, null);
    }

    /**
     * 抛出异常
     *
     * @param \Throwable|null $th
     * @return mixed|void
     * @throws HttpClientException
     * @author Chen Lei
     * @date 2020-12-04
     */
    public function throw(Throwable $th = null)
    {
        throw new HttpClientException($th->getMessage(), $th->getCode(), $this, $th);
    }

    /**
     * Dynamically proxy other methods to the underlying response.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed|void
     * @throws \App\Support\HttpClient\HttpClientException
     */
    public function __call($method, $parameters)
    {
        // 调用 'get', 'post', 'put', 'patch', 'delete', 'head' 方法
        if (in_array(strtolower($method), ['get', 'post', 'put', 'patch', 'delete', 'head'])) {
            return $this->send($method, ...$parameters);
        }

        // 调用 http方法
        if (method_exists($this->http, $method)) {
            $this->http->{$method}(...$parameters);
            return $this;
        }
    }
}
