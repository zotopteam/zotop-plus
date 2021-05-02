<?php

namespace Zotop\Image\Filters;

use Intervention\Image\Image;
use Zotop\Image\Filter;

class Resize extends Filter
{
    /**
     * 图片宽度
     *
     * @var integer
     */
    public $with;

    /**
     * 图片宽度
     *
     * @var integer
     */
    public $height;

    /**
     * 是否限制图像的当前宽高比例
     *
     * @var bool
     */
    public $aspectRatio = true;

    /**
     * Determines whether keeping the image from being upsized.
     *
     * @var bool
     */
    public $upsize = true;

    /**
     * 初始化
     *
     * @param string $parameter 图片宽高的快捷形式 如：600-500 或者 300
     */
    public function __construct($parameter = null)
    {
        // 300-300 或者 300
        if ($parameter) {
            [$this->width, $this->height] = array_pad(explode('-', $parameter), 2, null);
        }
    }

    /**
     * 应用滤器
     *
     * @param \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $this->width = intval($this->width);
        $this->height = intval($this->height) ? intval($this->height) : $this->width;

        if ($this->width && $this->height) {
            return $image->resize($this->width, $this->height, function ($constraint) {
                if ($this->aspectRatio) {
                    $constraint->aspectRatio();
                }
                if ($this->upsize) {
                    $constraint->upsize();
                }
            });
        }

        return $image;
    }
}
