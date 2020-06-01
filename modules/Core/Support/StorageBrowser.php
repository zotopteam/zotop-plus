<?php

namespace Modules\Core\Support;

use App\Modules\Facades\Module;
use App\Support\Facades\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Traits\Macroable;
use stdClass;

class StorageBrowser
{
    /**
     * Storage 对象
     * @var Storage
     */
    public $storage;

    /**
     * 磁盘
     * 
     * @var string
     */
    public $disk;

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
     * 当前url参数
     * 
     * @var array
     */
    public $parameters = [];

    /**
     * route参数 + request参数
     * 
     * @var array
     */
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
    public function __construct(Request $request, $disk = 'public', $root = 'uploads', $dir = '')
    {
        $this->disk    = $disk;
        $this->storage = Storage::disk($disk);
        $this->root    = $root;
        $this->dir     = $dir ?: $request->input('dir');
        $this->path    = trim(trim($this->root, '/') . '/' . trim($this->dir, '/'), '/');
        $this->route   = app('router')->getCurrentRoute()->getName();
        $this->params  = array_merge(app('router')->getCurrentRoute()->parameters(), $request->all());
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

        if ($this->dir) {
            $segments = array_merge([''], $segments);
        }

        while ($segments) {
            $dir  = implode('/', $segments);
            $name = array_pop($segments);
            $href = route($this->route, array_merge($this->params, ['dir' => trim($dir, '/')]));
            $position[] = (object) compact('name', 'href');
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

        if ($this->dir) {
            $segments = array_merge([''], $segments);
        }

        if (count($segments) > 1) {
            array_pop($segments);
            $dir  = implode('/', $segments);
            $name = end($segments);
            $href = route($this->route, array_merge($this->params, ['dir' => trim($dir, '/')]));
            $upfolder = (object) compact('name', 'href');
            return $upfolder;
        }

        return null;
    }

    /**
     * 创建文件夹
     * @return object
     */
    public function createFolder()
    {
        return (object) [
            'title' => trans('core::folder.create'),
            'class' => 'js-prompt',
            'icon'  => 'fa fa-folder',
            'name'  => 'name',
            'url'   => route('core.storage.folder.create', array_merge($this->params, ['path'  => $this->path])),
        ];
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

        foreach ($this->storage->directories($this->path) as $path) {

            $folder = new stdClass();

            $folder->type     = 'folder';
            $folder->name     = basename($path);
            $folder->icon     = 'fa fa-folder';
            $folder->path     = $path;
            $folder->realpath = $this->storage->path($path);
            $folder->size     = '';
            $folder->time     = Carbon::parse($this->storage->lastModified($path));
            $folder->href     = route($this->route, array_merge($this->params, ['dir' => $path]));
            $folder->typename = trans('core::folder.type');

            $folder->action   = [
                'rename' => [
                    'text'  => trans('master.rename'),
                    'icon'  => 'fa fa-edit',
                    'class' => 'js-prompt',
                    'attrs' => [
                        'data-url'   => route('core.storage.folder.rename', array_merge($this->params, ['path'  => $path])),
                        'data-name'  => 'name',
                        'data-value' => $folder->name,
                    ]
                ],
                'delete' => [
                    'text' => trans('master.delete'),
                    'icon' => 'fa fa-times',
                    'href' => route('core.storage.folder.delete', array_merge($this->params, ['path'  => $path])),
                    'class' => 'js-delete',
                ],
            ];

            $folders[] = Filter::fire('core.storagebrower.folder', $folder, $this);
        }

        return collect($folders);
    }

    public function files()
    {
        $files = [];

        foreach ($this->storage->files($this->path) as $path) {

            $file = new stdClass();

            $file->name      = basename($path);
            $file->size      = size_format($this->storage->size($path));
            $file->time      = Carbon::parse($this->storage->lastModified($path));
            $file->path      = $path;
            $file->realpath  = $this->storage->path($path);
            $file->mimetype  = $this->storage->mimeType($path);
            $file->extension = File::extension($path);
            $file->type      = File::humanType($path) ?? 'other';
            $file->icon      = File::icon($path);
            $file->url       = $this->storage->url($path);
            $file->typename  = trans('core::file.type.' . $file->type);
            $file->width     = 0;
            $file->height    = 0;

            // 获取图片宽高
            if ($file->type == 'image' && $imagesize = @getimagesize($file->realpath)) {
                [$file->width, $file->height] = $imagesize;
            }

            // 忽略点开头的文件
            if ($file->name[0] == '.') {
                continue;
            }

            $file->action    = [
                'view' => [
                    'text'  => trans('master.view'),
                    'icon'  => 'fa fa-eye',
                    'class' => 'js-image',
                    'attrs' => [
                        'data-url'  => preview($this->disk . ':' . $path),
                        'data-info' => $file->size . ($file->width ? " / {$file->width}px × {$file->height} px" : ''),
                    ],

                ],
                'download' => [
                    'text' => trans('master.download'),
                    'icon' => 'fa fa-download',
                    'href' => route('core.storage.file.download', array_merge($this->params, ['path'  => $path])),
                ],
                'rename' => [
                    'text'  => trans('master.rename'),
                    'icon'  => 'fa fa-edit',
                    'class' => 'js-prompt',
                    'attrs' => [
                        'data-url'   => route('core.storage.file.rename', array_merge($this->params, ['path'  => $path])),
                        'data-name'  => 'name',
                        'data-value' => $file->name,
                    ]
                ],
                'delete' => [
                    'text' => trans('master.delete'),
                    'icon' => 'fa fa-times',
                    'href' => route('core.storage.file.delete', array_merge($this->params, ['path'  => $path])),
                    'class' => 'js-delete',
                ],
            ];



            $files[] = Filter::fire('core.storagebrower.file', $file, $this);
        }
        debug($files);
        return collect($files);
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return this
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (!Str::startsWith($method, 'with')) {
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
