<?php
if (!function_exists('allow')) {

    /**
     * 检查当前用户是否拥有权限
     *
     * @param string $permission 权限节点
     * @return bool
     */
    function allow(string $permission)
    {
        return auth()->user()->allow($permission);
    }
}

if (!function_exists('image')) {

    /**
     * 根据图片Url获取图片访问完整路径或者缩略图路径
     *
     * @param string $url
     * @param int|null $width
     * @param int|null $height
     * @param bool $fit
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @author Chen Lei
     * @date 2020-11-17
     */
    function image(string $url, $width = null, $height = null, $fit = true)
    {
        $path = public_path($url);

        // 如果图片不存在
        if (empty($url) || !File::exists($path)) {
            $url = app('themes')->asset('img/empty.jpg');
            $path = app('themes')->path('assets/img/empty.jpg');
        }

        // 如果缩放图片
        if ($width || $height) {
            $url = md5($url);
            $url = 'thumbnails/' . substr($url, 0, 2) . '/' . substr($url, 2, 2) . '/' . $url . '-' . intval($width) . '-' . intval($height) . '-' . intval($fit) . '.' . File::extension($path);
            $file = public_path($url);

            // 缩略图不存在，或者原图片被修改
            if (!File::exists($file) || File::lastModified($file) < File::lastModified($path)) {

                // 如果目录不存在，尝试创建
                if (!File::isDirectory($dir = dirname($file))) {
                    File::makeDirectory($dir, 0775, true);
                }

                // 拷贝图片到临时目录
                File::copy($path, $file);

                // 图片处理
                if ($width && $height && $fit) {
                    app('image')->make($file)->fit($width, $height)->save();
                } else {
                    app('image')->make($file)->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save();
                }
            }
        }

        return url($url);
    }
}
