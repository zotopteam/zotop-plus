<?php
namespace App\Support\ImageFilters;

use App\Support\ImageFilter;
use Intervention\Image\Image;

class Fit extends ImageFilter
{
    /**
     * 图片宽度
     * @var integer
     */
    public $with;

    /**
     * 图片宽度
     * @var integer
     */
    public $height;

    /**
     * 裁剪位置
     * @var string
     */
    public $position = 'center';

    /**
     * Determines whether keeping the image from being upsized.
     *
     * @var bool
     */
    public $upsize = true;

    /**
     * 初始化
     */
    public function __construct($parameter=null)
    {
        // 300-300 或者 300
        if ($parameter) {
            [$this->width, $this->height] = array_pad(explode('-', $parameter), 2, null);
        }
    }

    /**
     * 应用滤器
     * @param  Image  $image
     * @return Image
     */
    public function applyFilter(Image $image)
    {
        $this->width  = intval($this->width);
        $this->height = intval($this->height) ? intval($this->height) : $this->width;

        if ($this->width && $this->height) {
            return $image->fit($this->width, $this->height, function($constraint){
                if ($this->upsize) {
                    $constraint->upsize();
                }
            }, $this->position);
        }

        return $image;
    }  
}
