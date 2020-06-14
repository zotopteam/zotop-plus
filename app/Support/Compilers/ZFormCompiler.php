<?php

namespace App\Support\Compilers;

/**
 * 模板扩展，解析点格式的数组
 * 
 * @package App\Support
 */
class ZFormCompiler
{
    /**
     * 解析 {xxx name='aaa'} 标签
     *
     * @param  string  $value
     * @return string
     */
    public function compile($value)
    {
        $value = $this->compileFormOpeningTag($value);
        $value = $this->compileFormClosingTag($value);
        $value = $this->compileFieldTag($value);
        return $value;
    }

    /**
     * 解析form标签，如： {form bind="$config" method="post"} 
     *
     * @param string $value
     * @return string
     */
    private function compileFormOpeningTag($value)
    {
        // 解析 {form}
        $pattern = "/(@)?
            <
                \s*
                z-form
                \s*
                (?<attributes>
                    (?:
                        \s+
                        [\w\-:.@]+
                        (
                            =
                            (?:
                                \\\"[^\\\"]*\\\"
                                |
                                \'[^\']*\'
                                |
                                [^\'\\\"=<>]+
                            )
                        )?
                    )*
                    \s*
                )
            >
        /x";

        $value   = preg_replace_callback($pattern, function ($matches) {

            // 如果@开头，直接返回去掉@后的字符串
            if ($matches[1]) {
                return substr($matches[0], 1);
            }

            // 标签字符串转换为数组字符串
            // 转换前  bind="$config" route="site.config.seo" method="post" id="config" autocomplete="off"
            // 转换后 'bind' => $config, 'route' => 'site.config.seo', 'method' => 'post', 'id' => 'config', 'autocomplete' => 'off'            
            $attributes = $this->convertAttributes($matches['attributes']);

            return "<?php echo Form::open(" . $attributes . "); ?>\r\n";
        }, $value);

        return $value;
    }

    /**
     * 解析form关闭标签，如： {/form} 
     *
     * @param string $value
     * @return string
     */
    private function compileFormClosingTag($value)
    {
        // 解析 {/form}
        $pattern = '/(@)?(<\/\s*z-form\s*>)/x';
        $value   = preg_replace_callback($pattern, function ($matches) {
            return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::close(); ?>\r\n";
        }, $value);

        return $value;
    }

    /**
     * 解析field标签，如： {field type="editor" options="Module::data(……)"} 
     *
     * @param string $value
     * @return string
     */
    private function compileFieldTag($value)
    {
        // 解析 {field}
        $pattern = "/(@)?
            <
                \s*
                z-([\w\-]*)
                \s*
                (?<attributes>
                    (?:
                        \s+
                        [\w\-:.@]+
                        (
                            =
                            (?:
                                \\\"[^\\\"]*\\\"
                                |
                                \'[^\']*\'
                                |
                                [^\'\\\"=<>]+
                            )
                        )?
                    )*
                    \s*
                )
            (\/)?>
        /x";

        $value   = preg_replace_callback($pattern, function ($matches) {
            //dd($matches);
            // 如果@开头，直接返回去掉@后的字符串
            if ($matches[1]) {
                return substr($matches[0], 1);
            }

            $method = str_replace('-', '_', $matches[2]);

            // 标签字符串转换为数组字符串
            // 转换前 type="submit" form="config" value="trans('master.save')" class="btn btn-primary"
            // 转换后 'type' => 'submit', 'form' => 'config', 'value' => trans('master.save'), 'class' => 'btn btn-primary'
            $attributes = $this->convertAttributes($matches['attributes']);

            return "<?php echo Form::" . $method . "(" . $attributes . "); ?>\r\n";
        }, $value);

        return $value;
    }

    /**
     * 从标签字符串中解析出标签数组
     * 转换前 type="submit" form="config" value="trans('master.save')" class="btn btn-primary"
     * 转换后 'type' => 'submit', 'form' => 'config', 'value' => trans('master.save'), 'class' => 'btn btn-primary'
     * @param  string  $attributeString
     * @return string
     */
    protected function convertAttributes(string $attributeString)
    {
        $pattern = '/
            (?<attribute>[\w\-:.@]+)
            (
                =
                (?<value>
                    (
                        \"[^\"]+\"
                        |
                        \\\'[^\\\']+\\\'
                        |
                        [^\s>]+
                    )
                )
            )?
        /x';

        if (preg_match_all($pattern, $attributeString, $matches, PREG_SET_ORDER)) {

            // 转换全部标签为数组的字符串格式
            $attributes = collect($matches)->mapWithKeys(function ($match) {
                // 获取标签名称
                $attribute = $match['attribute'];
                // 获取标签值，如果值不存在，直接转换为true, 如 checked 转换为 checked=true
                $value = isset($match['value']) ? $match['value'] : 'true';
                // 去除前后的双引号和单引号
                $value = trim($value, '"');
                $value = trim($value, "'");
                // 某些值无法被判断为动态值，需要在属性标签前面明确标注英文冒号
                // 处理明确标记为动态值的标签 :bind="$config" 
                if (strpos($attribute, ':') === 0) {
                    $attribute = ltrim($attribute, ':');
                } else {
                    $value = $this->convertValue($value);
                }
                return [$attribute => $value];
            })->map(function ($value, $attribute) {
                return "'{$attribute}' => {$value}";
            })->implode(', ');

            return '[' . $attributes . ']';
        }

        return '[]';
    }

    /**
     * 转换参数值为数组真实类型
     * null, bool、 $a 、function()函数 和 A::method() 静态方法 直接返回
     * 字符串加上单引号
     * 
     * @param  mixed  $value 标签值
     * @return boolean
     */
    protected function convertValue($value)
    {
        // 如果是以$开头为变量  'key'=>$value, 'key'=>1234
        if (strpos($value, '$') === 0 || is_numeric($value)) {
            return $value;
        }

        // 'null','true','false' 直接返回  'key'=>null,  'key'=>false,  'key'=>true 
        if (in_array(strtolower($value), ['null', 'true', 'false'])) {
            return $value;
        }
        // [……]，数组直接返回， 'key'=>[……],
        if (preg_match('/^\[.*\]$/', $value)) {
            return $value;
        }
        // test(……)，A::test(……) 函数或者方法直接返回 'key'=>test(……),'key'=>A::test(……),
        if (preg_match('/[a-zA-Z\\_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(.*?\)/', $value)) {
            return $value;
        }
        // 字符串类型加单引号后返回，TODO：可能有些数据需要处理 addslashes($value)
        return "'" . $value . "'";
    }
}
