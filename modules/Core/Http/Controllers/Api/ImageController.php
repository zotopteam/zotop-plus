<?php

namespace Modules\Core\Http\Controllers\Api;

use App\Modules\Routing\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends ApiController
{
    /**
     * 图片预览
     * 
     * @return Response
     */
    public function preview(Request $request)
    {
        $path = $request->path;
        $size = $request->size;

        // 预览存储系统文件 public:uploads/aaa.png
        if (strpos($path, ':')) {
            [$disk, $path] = explode(':', $path);
            $data = Storage::disk($disk)->get($path);
        } else {
            $data = File::get($path);
        }

        // 缓存并处理图片预览
        $preview = Image::cache(function($image) use ($data, $size) {
            $image = $image->make($data);
            // 尺寸 resize:300:300:resize,fit:300:200
            if ($size) {
                [$size, $width, $height] = explode(':', $size);
                if ($size == 'fit') {
                    $image->fit($width, $height);
                } else {
                    $image->resize($width, $height, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();       
                    });
                }
            }
            return $image;
        }, 10, true);

        return $preview->response();
    }
}
