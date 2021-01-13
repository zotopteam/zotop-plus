<?php

namespace Modules\Core\Support\ImageFilters;

use App\Support\ImageFilter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Image;

class Watermark extends ImageFilter
{
    /**
     * 是否开启
     *
     * @var boolean
     */
    public $enabled = true;

    /**
     * 大于此宽度加水印
     *
     * @var integer
     */
    public $width = 1920;

    /**
     * 大于此高度加水印
     *
     * @var integer
     */
    public $height = 1200;

    /**
     * 图片品质
     *
     * @var integer
     */
    public $quality = 100;

    /**
     * 水印透明度
     *
     * @var integer
     */
    public $opacity = 60;

    /**
     * 水印位置
     *
     * @var string
     */
    public $position = 'bottom-right';

    /**
     * 水印文字x轴偏移
     *
     * @var integer
     */
    public $offset_x = 10;

    /**
     * 水印文字轴偏移
     *
     * @var integer
     */
    public $offset_y = 10;

    /**
     * 水印类型，可选择值为 text=文字水印,image=图片水印
     *
     * @var string
     */
    public $type = 'text';

    /**
     * 文字水印的水印文字
     *
     * @var string=
     */
    public $text = '';

    /**
     * 文字水印字体，相对根目录路径
     *
     * @var string
     */
    public $font_file = 'resources/fonts/default.otf';

    /**
     * 文字水印文字大小
     *
     * @var string
     */
    public $font_size = 36;

    /**
     * 文字水印文字颜色
     *
     * @var string=
     */
    public $font_color = '#ffffff';

    /**
     * 文字水印角度
     *
     * @var string=
     */
    public $font_angle = 0;

    /**
     * 图片水印的水印图片
     *
     * @var string=
     */
    public $image = '';

    /**
     * 初始化
     */
    public function __construct($parameter = null)
    {
        // 读取核心设置
        $this->enabled = config('core.image.watermark.enabled', $this->enabled);
        $this->width = config('core.image.watermark.width', $this->width);
        $this->height = config('core.image.watermark.height', $this->height);
        $this->quality = config('core.image.watermark.quality', $this->quality);

        $this->opacity = config('core.image.watermark.opacity', $this->opacity);
        $this->position = config('core.image.watermark.position', $this->position);
        $this->offset_x = config('core.image.watermark.offset_x', $this->offset_x);
        $this->offset_y = config('core.image.watermark.offset_y', $this->offset_y);

        $this->type = config('core.image.watermark.type', $this->type);
        $this->text = config('core.image.watermark.text', $this->type);
        $this->font_file = config('core.image.watermark.font_file', $this->font_file);
        $this->font_size = config('core.image.watermark.font_size', $this->font_size);
        $this->font_color = config('core.image.watermark.font_color', $this->font_color);
        $this->font_angle = config('core.image.watermark.font_angle', $this->font_angle);
        $this->image = config('core.image.watermark.image', $this->image);
    }

    /**
     * 设置偏移尺寸
     *
     * @param integer $x x轴偏移
     * @param integer $y y轴偏移
     * @return $this
     */
    public function offset($x, $y)
    {
        $this->offset_x = $x;
        $this->offset_y = $y;

        return $this;
    }

    /**
     * 设置文字
     *
     * @param string 水印文字
     * @return $this
     */
    public function text($text)
    {
        if ($text) {
            $this->text = $text;
            $this->type = 'text';
            $this->enabled = true;
        }

        return $this;
    }

    /**
     * 设置水印图片
     *
     * @param string 水印文字
     * @return $this
     */
    public function image($image)
    {
        if ($image) {
            $this->image = $image;
            $this->type = 'image';
            $this->enabled = true;
        }

        return $this;
    }

    /**
     * position参数统一使用图片的position设置（key值） 文字的位置需要对应转换
     *
     * @param Image image 图片对象
     * @return mixed
     */
    protected function getPosition($image)
    {
        $positions = [
            'top-left'     => 'top-left',
            'top'          => 'top-center',
            'top-right'    => 'top-right',
            'left'         => 'middle-left',
            'center'       => 'middle-center',
            'right'        => 'middle-right',
            'bottom-left'  => 'bottom-left',
            'bottom'       => 'bottom-center',
            'bottom-right' => 'bottom-right',
        ];

        $position = strtolower($this->position);

        // 如果postion设置错误，统一返回 bottom-right
        if (!in_array($position, array_keys($positions))) {
            $position = 'bottom-right';
        }

        // 图片位置直接返回
        if ($this->type == 'image') {
            return $position;
        }

        // 文字返回返回转换后的位置
        // 相对于给定基点(x/y)的垂直和水平文本对齐方式
        [$x, $y] = [$this->offset_x, $this->offset_y];
        [$valign, $align] = explode('-', $positions[$position]);

        if ($align == 'center') {
            $x = $image->width() / 2;
        }

        if ($align == 'right') {
            $x = $image->width() - $this->offset_x;
        }

        if ($valign == 'middle') {
            $y = $image->height() / 2;
        }

        if ($valign == 'bottom') {
            $y = $image->height() - $this->offset_y;
        }

        return compact('x', 'y', 'align', 'valign');
    }

    /**
     * 获取文字字体文件路径
     * 路径设置为True Type字体文件或GD库内部字体之一的1到5之间的整数值
     *
     * @return mixed
     */
    protected function getFontFile()
    {
        return base_path($this->font_file);
    }

    /**
     * 获取文字字体大小
     * 最小12
     *
     * @return mixed
     */
    protected function getFontSize()
    {
        return max(12, intval($this->font_size));
    }

    /**
     * 获取文字字体角度
     * 0=水平 90=垂直 范围值：0-360
     *
     * @return mixed
     */
    protected function getFontAngle()
    {
        $angle = intval($this->font_angle);
        $angle = max(0, $angle);
        $angle = min(360, $angle);

        return $angle;
    }

    /**
     * 获取文字字体颜色
     * 叠加透明度
     *
     * @return mixed
     */
    protected function getFontColor()
    {
        $color = $this->font_color ?: '#ffffff';
        $opacity = max(1, $this->opacity) / 100;

        // [255,255,255]
        if (is_array($color) && count($color) == 3) {
            return [$color[0], $color[1], $color[2], $opacity];
        }

        // #ff6600,ff66ff,f60
        $hex = str_replace('#', '', $color);

        if (strlen($hex) == 3) {
            return [
                hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)),
                hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)),
                hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)),
                $opacity,
            ];
        }

        if (strlen($hex) == 6) {
            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
                $opacity,
            ];
        }

        throw new InvalidArgumentException("Watermark config color [$color] not correct.");
    }

    /**
     * 获取本地水印图片完整地址
     *
     * @return string
     */
    protected function getImagePath()
    {
        $image = public_path($this->image);

        if (file_exists($image)) {
            return $image;
        }

        return $this->getRemoteToLocalImagePath();
    }

    /**
     * 尝试将远程文件下载到本地，返回本地的图片完整地址
     *
     * @return string
     */
    protected function getRemoteToLocalImagePath()
    {
        // 必须是http图片
        if (!Str::startsWith($this->image, ['http://', 'https://'])) {
            return null;
        }

        // 本地临时文件目录地址
        $image = public_path('previews/watermarks/' . md5($this->image) . '.png');

        if (!file_exists($image)) {
            try {
                // 获取图片
                $data = Http::timeout(1)->get($this->image)->body();
                // 保存到本地
                ImageFacade::make($data)->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($image);
            } catch (\Exception $e) {
                $image = null;
            }
        }

        return $image;
    }

    /**
     * 获取水印
     *
     * @return Image
     */
    protected function getImage()
    {
        // 如果文件存在
        if ($image = $this->getImagePath()) {

            // 水印图片实例
            $image = ImageFacade::make($image);
            // 水印透明度
            $image->opacity($this->opacity);

            return $image;
        }
        return null;
    }

    /**
     * 插入图片水印
     *
     * @return Image
     */
    public function insertImage($image)
    {
        // 插入水印
        if ($watermark = $this->getImage()) {
            $image->insert($watermark, $this->getPosition($image), $this->offset_x, $this->offset_y);
        }

        return $image;
    }

    /**
     * 插入文字水印
     *
     * @return Image
     */
    public function insertText($image)
    {
        if ($this->text && $file = $this->getFontFile()) {

            $position = $this->getPosition($image);
            $x = $position['x'];
            $y = $position['y'];
            $align = $position['align'];
            $valign = $position['valign'];
            $size = $this->getFontSize();
            $color = $this->getFontColor();
            $angle = $this->getFontAngle();

            $image->text($this->text, $x, $y, function ($font) use ($file, $size, $color, $angle, $align, $valign) {
                $font->file($file);
                $font->size($size);
                $font->color($color);
                $font->align($align);  //left, right or center.
                $font->valign($valign); //top, bottom or middle.
                $font->angle($angle);
            });
        }
        return $image;
    }

    /**
     * 应用滤器
     *
     * @param Image $image
     * @return Image
     */
    public function applyFilter(Image $image)
    {
        if ($this->enabled && $image->width() >= $this->width && $image->height() >= $this->height) {
            return $this->{"insert" . Str::studly($this->type)}($image);
        }

        return $image;
    }
}
