<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;

class PluploadController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 图片字段上传
     * 
     * @return [type] [description]
     */
    public function image(Request $request)
    {
        // 获得multipart_params传过来的数据
        $params = $request->all();

        return \Plupload::receive('file', function ($tempfile) use($params)
        {            
            $basepath = '/uploads/'.date('Y/m/d',time());           
            $filepath = public_path($basepath);
            $filename = date('YmdHisu', time()).rand(1000,9999).'.'.\File::extension($tempfile->getClientOriginalName());

            // 如果目录不存在，尝试创建目录
            if ( !\File::exists($filepath) )
            {
                \File::makeDirectory($filepath, 0775, true);
            }           

            // 移动文件
            $file = $tempfile->move($filepath, $filename);
            
            // TODO 文件判断和处理
            // coding……
            
            // 文件路径，相对于public目录
            $path = $basepath.'/'.$filename;
            $url  = $path;

            return [
                'status'    => true,
                'name'      => $params['filename'],
                'type'      => $file->getMimeType(),
                'extension' => $file->getExtension(),
                'size'      => $file->getSize(),
                'path'      => $path,
                'url'       => $url,
            ];
        });    
    }
}
