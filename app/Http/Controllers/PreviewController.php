<?php

namespace App\Http\Controllers;

use Zotop\Support\ImageFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PreviewController extends Controller
{
    /**
     * 图片预览
     *
     * @return \Illuminate\Http\Response
     */
    public function image(Request $request, $filter, $disk, $path)
    {
        // 获取全部存储盘
        $disks = array_keys(config('filesystems.disks'));

        // 如果是存储盘文件，则获取存储盘文件内容，否则为生成文件路径
        $data = in_array($disk, $disks) ? Storage::disk($disk)->get($path) : base_path($path);

        // 缓存并处理图片预览
        $preview = Image::cache(function ($image) use ($data, $filter) {

            // 生成图片实例
            $image = $image->make($data);

            // 应用图片滤镜，比如尺寸 resize:300-300 fit:300-200
            $image = ImageFilter::apply($image, $filter);

            return $image;
        }, config('image.preview.dynamic.lifetime', 10), true);

        return $preview->response();
    }
}
