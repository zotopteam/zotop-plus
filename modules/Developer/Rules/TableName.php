<?php

namespace Modules\Developer\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TableName implements Rule
{
    /**
     * 模块
     *
     * @var string
     */
    protected $module;

    /**
     * 错误提示
     *
     * @var string
     */
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @param $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 表名必须等于模块名称或者已经模块名称加下划线开头
        $moduleName = $this->module->getLowerName();

        if (!($value == $moduleName || Str::startsWith($value, $moduleName . '_')) || Str::endsWith($value, '_')) {
            $this->message = trans('developer::table.name.error', [$moduleName]);
            return false;
        }

        // 检查数据表是否存在
        if (Schema::hasTable($value)) {
            $this->message = trans('developer::table.exists', [$value]);
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
