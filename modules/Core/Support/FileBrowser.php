<?php
namespace Modules\Core\Support;

use Illuminate\Http\Request;
use Modules\Core\Support\Facades\Format;
use Filter;
use File;

class FileBrowser
{
    /**
     * 开始地址
     * 
     * @var string
     */
    public $root;    

    /**
     * 浏览目录
     * 
     * @var string
     */
    public $dir;

    /**
     * 当前相对根目录的路径
     * @var string
     */
    public $path;

    /**
     * 当前绝对路径
     * @var string
     */
    public $realpath;    

    /**
     * 当前参数
     * 
     * @var array
     */
    public $parameters = [];
    public $params = [];



    /**
     * 当前页面路由名称
     * 
     * @var string
     */
    public $route;

    /**
     * 初始化
     * 
     * @param string $root 根目录，默认为上传目录
     */
    public function __construct(Request $request, $root='public/uploads', $dir='')
    {
        $this->root       = $root;
        $this->dir        = $dir ?: $request->input('dir');
        $this->path       = $this->root.'/'.trim($this->dir,'/');
        $this->realpath   = realpath(base_path($this->path));
        $this->route      = app('router')->getCurrentRoute()->getName();
        $this->parameters = app('router')->getCurrentRoute()->parameters();
        $this->params     = $request->all();
    }

    /**
     * 获取位置导航数组
     * 
     * @return collect
     */
    public function position()
    {
        $position = [];
        $segments = explode('/', $this->dir);
        while ($segments) {
            $dir  = implode('/', $segments);
            $name = array_pop($segments);
            $href = route($this->route, $this->parameters + ['dir'=>$dir] + $this->params);
            $position[] = (object) compact('name','href');
        }
        $position = array_reverse($position);
        return collect($position);     
    }

    /**
     * 上级文件夹
     * 
     * @return [type] [description]
     */
    public function upfolder()
    {
        $segments = explode('/', $this->dir);

        if (count($segments) > 1) {
            array_pop($segments);
            $dir  = implode('/', $segments);
            $name = array_last($segments);
            $href = route($this->route, $this->parameters + ['dir'=>$dir] + $this->params);
            $upfolder = (object) compact('name','href');
            return $upfolder;
        }

        return null;
    }

    /**
     * 获取全部文件夹
     * 
     * @return array
     */
    public function folders()
    {
        // 文件夹组装
        $folders =  [];

        foreach (File::directories($this->realpath) as $realpath) {
            $type     = 'folder';
            $name     = basename($realpath);
            $icon     = 'fa fa-folder';
            $path     = path_base($realpath);
            $size     = '';
            $time     = Format::date(File::lastModified($realpath), 'datetime');
            $href     = route($this->route, $this->parameters + ['dir'=>$this->dir.'/'.$name] + $this->params);
            $typename = trans('core::folder.type');
            $folder   = Filter::fire('core.filebrower.folder',
                            compact('type','name','icon','path','size','time','href','realpath','typename'),
                            $realpath
                        );

            $folders[] = array_object($folder);
        }

        return collect($folders);    
    }

    public function files()
    {
        $files = [];
        foreach (File::files($this->realpath) as $realpath) {
            $name   = $realpath->getFileName();
            $size   = Format::size($realpath->getSize());
            $time   = Format::date($realpath->getMTime(),'datetime');
            $path   = path_base($realpath);
            $mime   = File::mime($realpath);
            $type   = File::humanType($realpath) ?? 'other';
            $icon   = File::icon($realpath);

            $width   = 0;
            $height  = 0;

            if ($type == 'image' && $imagesize = @getimagesize($realpath)) {
                list($width, $height) = $imagesize;
            }

            $url = '';

            if (starts_with($realpath, public_path())) {
                $url = str_after($realpath, public_path());
                $url = Format::url($url);
            }

            $typename = trans('core::file.type.'.$type);
            $file     = Filter::fire('core.filebrower.file',
                            compact('name','path','size','time','mime','type','icon','url','width','height','realpath','typename'),
                            $realpath
                        );            

            $files[] = array_object($file);
        }

        return collect($files);
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Modules\Core\Support\Watermark
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! Str::startsWith($method, 'with')) {
            throw new BadMethodCallException("Method [$method] does not exist on view.");
        }

        return $this->with(Str::camel(substr($method, 4)), $parameters[0]);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }         
}
