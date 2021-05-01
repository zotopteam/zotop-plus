<?php

namespace Modules\Developer\Rules;

use Zotop\Themes\Facades\Theme;
use Illuminate\Contracts\Validation\Rule;

class ThemeName implements Rule
{
    /**
     * 错误提示
     * @var string
     */
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // 验证失败
        if (Theme::find($value)) {
            $this->message = trans('master.existed', [$value]);
            return false;
        }

        // 验证通过
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
