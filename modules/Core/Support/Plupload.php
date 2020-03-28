<?php

namespace Modules\Core\Support;

use Closure;
use Illuminate\Http\UploadedFile;

class Plupload
{
    /**
     * 临时文件最大生命周期，单位秒
     * @var integer
     */
    protected $maxFileAge = 600; //秒

    /**
     * 请求
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->request = app('request');
    }

    /**
     * 获取临时目录
     * @return [type] [description]
     */
    public function getFilePath($file=null)
    {
        $path = storage_path('plupload');

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $file ? $path.'/'.$file : $path;
    }

    /**
     * 上传单个文件
     * 
     * @param  string  $name    字段名称
     * @param  Closure $handler 回调
     * @return mixed
     */
    public function receiveSingle($name, Closure $handler)
    {
        if ($this->request->file($name)) {
            return $handler($this->request->file($name));
        }

        return false;
    }

    /**
     * 追加文件
     * @param  string       $filePathPartial 临时文件地址
     * @param  UploadedFile $file            上传文件
     * @return void
     */
    private function appendData($filePathPartial, UploadedFile $file)
    {
        if (! $out = @fopen($filePathPartial, 'ab')) {
            abort(102, 'Failed to open output stream.');
        }

        if (! $in = @fopen($file->getPathname(), 'rb')) {
            abort(101, 'Failed to open input stream.');
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
    }

    /**
     * 分片上传
     * 
     * @param  string  $name    文件地址
     * @param  Closure $handler 闭包
     * @return mixed
     */
    public function receiveChunks($name, Closure $handler)
    {
        $result = false;

        if ($file = $this->request->file($name)) {

            $chunk    = (int) $this->request->get('chunk', false);
            $chunks   = (int) $this->request->get('chunks', false);
            $fileName = $this->request->get('name');
            $filePath = $this->getFilePath($fileName . '.part');

            $this->removeOldData($filePath);
            $this->appendData($filePath, $file);

            // 全部分片上传完成
            if ($chunk == $chunks - 1) {
                $file = new UploadedFile($filePath, $fileName, 'blob', UPLOAD_ERR_OK, true);
                $result = $handler($file);
                @unlink($filePath);
            }
        }

        return $result;
    }

    /**
     * 删除临时文件
     * @param  string $filePath 文件
     * @return void
     */
    public function removeOldData($filePath)
    {
        if (file_exists($filePath) && filemtime($filePath) < time() - $this->maxFileAge) {
            @unlink($filePath);
        }
    }

    /**
     * 接收文件
     * @param  string  $name    字段名称
     * @param  Closure $handler 回调
     * @return array
     */
    public function receive($name, Closure $handler)
    {
        $response = [];
        $response['jsonrpc'] = '2.0';

        // 如果分片，这使用分片上传
        if ((bool) $this->request->get('chunks', false)) {
            $result = $this->receiveChunks($name, $handler);
        } else {
            $result = $this->receiveSingle($name, $handler);
        }

        $response['result'] = $result;

        return $response;
    }
}
