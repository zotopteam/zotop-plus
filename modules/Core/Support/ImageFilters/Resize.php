<?php
namespace Modules\Core\Support\ImageFilters;

use App\Support\ImageFilter;
use Intervention\Image\Image;

class Resize extends ImageFilter
{
    /**
     * 是否开启
     * @var boolean
     */
    protected $enabled = true;

    /**
     * 图片最大宽度
     * @var integer
     */
    protected $with;

    /**
     * 图片最大高度
     * @var integer
     */
    protected $height;

    /**
     * 图片品质
     * @var integer
     */
    protected $quality;

    /**
     * 是否限制图像的当前宽高比例
     *
     * @var bool
     */
    protected $aspectRatio = true;

    /**
     * Determines whether keeping the image from being upsized.
     *
     * @var bool
     */
    protected $upsize = true;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->enabled = config('core.image.resize.enabled');
        $this->width   = config('core.image.resize.width');
        $this->height  = config('core.image.resize.height');
        $this->quality = config('core.image.resize.quality');          
    }

    /**
     * 应用滤器
     * @param  Image  $image
     * @return Image
     */
    public function applyFilter(Image $image)
    {
        if ($this->enabled && ($image->width() > $this->width || $image->height() > $this->height)) {
            return $image->resize($this->width, $this->height, function($constraint) {
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
