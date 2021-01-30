<?php

namespace App\Modules\Maker;

use Illuminate\Support\Str;

trait OptionTypeTrait
{
    /**
     * 获取输入的 type
     *
     * @param string|null $key
     * @return string
     */
    protected function getTypeInput()
    {
        $type = strtolower(trim($this->option('type')));

        if (!in_array($type, ['backend', 'frontend', 'api'])) {
            $this->inputError("Incorrect --type entered, Must be one of backend|frontend|api");
        }

        return $type;
    }

    /**
     * 获取当前类型的配置
     *
     * @param null|string $key
     * @return string|array
     * @author Chen Lei
     * @date 2021-01-15
     */
    protected function getTypeConfig($key = null)
    {
        $type = $this->getTypeInput();

        if ($key) {
            return $this->getConfigTypes($type . '.' . $key);
        }

        return $this->getConfigTypes($type);
    }

    /**
     * 获取类的命名空间
     *
     * @param string|null $dirKey
     * @return string
     */
    protected function getClassNamespace($dirKey = null)
    {
        $dirKey = $dirKey ?? $this->dirKey;

        $namespace = $this->getDirNamespace($dirKey);;

        // 获取当前类型的目录
        if ($dir = $this->getTypeConfig("dirs.{$dirKey}")) {
            return $namespace . '\\' . Str::studly($dir);
        }

        return $namespace;
    }

    /**
     * 获取文件相对路径，不含模块路径，如：Http/Requests/Admin/RuleRequest.php
     *
     * @param string|null $dirKey
     * @return string
     */
    protected function getFilePath($dirKey = null)
    {
        $dirKey = $dirKey ?? $this->dirKey;

        $path = $this->getConfigDirs($dirKey);

        // 获取当前类型的目录
        if ($dir = $this->getTypeConfig("dirs.{$dirKey}")) {
            $path = $path . DIRECTORY_SEPARATOR . Str::studly($dir);
        }

        return $path . DIRECTORY_SEPARATOR . $this->getFileName();
    }
}
