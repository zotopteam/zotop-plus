<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Theme;
use Artisan;
use File;

class ThemesController extends AdminController
{

    /**
     * 将一个路径转化成名称-路径数组，用于生成position
     *
     *
     * @param array $dir 路径
     * @param string $s 分隔符
     *
     * @return array 包含全部路径的数组
     */    
    private function position($dir, $s='/')
    {

        $data = array();

        if ($dir) {

            $dirs = explode($s, $dir);

            $path = '';

            foreach($dirs as $d)
            {   
                if ($d == '.' || $d=='') {
                    $path = $d;
                } else {
                    $path = $path.$s.$d;
                }
                
                $data[$path] = $d;
            }
        }

        return $data;
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function index($type='')
    {
        $this->title       = trans('core::themes.title');
        $this->description = trans('core::themes.description');

        $this->themes      = Theme::getList($type);

        return $this->view();
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function files(Request $request, $name)
    {
        $theme          = Theme::find($name);
        
        $this->name     = $name;
        $this->dir      = $request->input('dir');
        $this->path     = $theme->path.DIRECTORY_SEPARATOR.$this->dir;
        $this->position = $this->position($this->dir);       
        $this->folders  = File::directories($this->path);
        $this->files    = File::files($this->path);
        $this->title    = trans('core::themes.files');

        return $this->view()->with('theme',$theme);
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($name='')
    {
        if ($name) {
            Artisan::call("theme:publish",[
                'theme' => $name
            ]);
        } else {
            Artisan::call('theme:publish');
        }

        return $this->success(trans('core::themes.publish.success'));    
    }

    /**
     * 上传主题
     *
     * @return Response
     */
    public function upload()
    {
  
    }        
}
