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
    public function __construct($config = [])
    {
        // 读取核心设置
        $config = array_merge(config('core.image.resize'), (array) $config);

        $this->enabled = Arr::get($config, 'enabled', $this->enabled);
        $this->width   = Arr::get($config, 'width', $this->width);
        $this->height  = Arr::get($config, 'height', $this->height);
        $this->quality = Arr::get($config, 'quality', $this->quality);          
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
