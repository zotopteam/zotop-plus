<?php

namespace Modules\Developer\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Module;

class TableName implements Rule
{
    /**
     * 模块名称
     * @var string
     */
    protected $module;

    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($module=null)
    {
        $this->module = $module;        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 表名必须等于模块名称或者已经模块名称加下划线开头
        if ($this->module && $module = Module::find($this->module) ) {
            $moduleName = $module->getLowerName();
            if (!($value == $moduleName || starts_with($value, $moduleName.'_')) || ends_with($value, '_')) {
                $this->message = trans('developer::table.name.error', [$moduleName]);
                return false;                
            }
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
