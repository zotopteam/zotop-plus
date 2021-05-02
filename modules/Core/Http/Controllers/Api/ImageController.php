<?php

namespace Modules\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Zotop\Image\Filter;
use Zotop\Modules\Routing\ApiController;

class ImageController extends ApiController
{
    /**
     * 图片预览
     *
     * @param \Illuminate\Http\Request $request
     * @param $filter
     * @param $disk
     * @param $path
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function preview(Request $request, $filter, $disk, $path)
    {

        // 预览存储系统文件 public:uploads/aaa.png
        if ($disk != 'root') {
            $data = Storage::disk($disk)->get($path);
        } else {
            $data = base_path($path);
        }

        // 缓存并处理图片预览
        $preview = Image::cache(function ($image) use ($data, $filter) {

            // 生成图片实例
            $image = $image->make($data);

            // 尺寸 resize:300-300 fit:300-200
            if ($filter = Filter::get($filter)) {
                $image->filter($filter);
            }

            // 尺寸 resize-300-300 fit-300-200
            // if ($size && $size != 'original') {
            //     [$size, $width, $height] = explode('-', $size);
            //     if ($size == 'fit') {
            //         $image->fit($width, $height);
            //     } else {
            //         $image->resize($width, $height, function($constraint){
            //             $constraint->aspectRatio();
            //             $constraint->upsize();
            //         });
            //     }
            // }

            return $image;

        }, 10, true);

        return $preview->response();
    }
}
