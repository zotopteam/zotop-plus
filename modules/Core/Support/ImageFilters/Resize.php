<?php
namespace Modules\Core\Support\ImageFilters;

use App\Support\ImageFilter;
use Illuminate\Support\Arr;
use Intervention\Image\Image;

class Resize extends ImageFilter
{
    /**
     * 是否开启
     * @var boolean
     */
    public $enabled = true;

    /**
     * 图片最大宽度
     * @var integer
     */
    public $width = 1920;

    /**
     * 图片最大高度
     * @var integer
     */
    public $height = 1200;

    /**
     * 图片品质
     * @var integer
     */
    public $quality = 100;

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
     */
    public function __construct($parameter = null)
    {
        // 读取核心设置
        $this->enabled = config('core.image.resize.enabled', $this->enabled);
        $this->width   = config('core.image.resize.width', $this->width);
        $this->height  = config('core.image.resize.height', $this->height);
        $this->quality = config('core.image.resize.quality', $this->quality);

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
