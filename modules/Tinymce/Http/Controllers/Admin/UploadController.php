<?php

namespace Modules\Tinymce\Http\Controllers\Admin;

use App\Modules\Routing\AdminController as Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * 首页
     *
     * @return Response
     */
    public function file(Request $request, $disk='public')
    {
        $file = $request->file('file');

        // 获取文件的可读类型
        $type = $file->getHumanType();
        $path = 'uploads/'.$type.'/'.date(config('core.upload.dir', 'Y/m/d'), time());

        // 保存文件
        $file = $file->store($path, $disk);

        // 存为公开文件
        $data = [
            'path' => Storage::disk($disk)->path($file),
            'url'  => Storage::disk($disk)->url($file),
        ];

        return ['location' => $data['url']];
    }
}
