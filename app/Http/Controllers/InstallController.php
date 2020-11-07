<?php

namespace App\Http\Controllers;

use App\Modules\Facades\Module;
use Exception;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\User;
use PDOException;

class InstallController extends Controller
{
    /**
     * app实例
     *
     * @var mixed|\Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * 本地语言
     *
     * @var string
     */
    protected $locale;

    /**
     * view 实例
     *
     * @var object
     */
    protected $view;

    /**
     * view 数据
     *
     * @var array
     */
    protected $data = [];

    /**
     * __construct
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        // app实例
        $this->app = app();

        // view实例
        $this->view = $this->app->make('view');

        // wizard
        $this->wizard = ['welcome', 'check', 'config', 'database', 'modules', 'finished'];

        // 获取当前动作指针
        $this->current = array_search(Route::getCurrentRoute()->getActionMethod(), $this->wizard);

        // 获取前一个动作
        $this->prev = ($this->current > 0) ? $this->wizard[$this->current - 1] : null;

        // 获取下一个动作
        $this->next = ($this->current < (count($this->wizard) - 1)) ? $this->wizard[$this->current + 1] : null;

        // 获取当前动作
        $this->current = $this->wizard[$this->current];
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $key 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     *
     * @access protected
     * @param string $key 模板显示变量
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * 显示View
     *
     * @return \Illuminate\Contracts\View\View|mixed
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function view()
    {
        return $this->view->make("install.{$this->current}", $this->data);
    }

    /**
     * TODO: Undocumented function
     *
     * @param array $msg 消息内容
     * @return \Illuminate\Http\JsonResponse
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function message(array $msg)
    {
        return response()->json($msg);
    }

    /**
     * 消息提示：success
     *
     * @param mixed $msg 消息内容
     * @param string $url 跳转路径
     * @param integer $time 跳转或者消息提示时间
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($msg, $url = '', $time = 2)
    {
        $msg = is_array($msg) ? $msg : ['content' => $msg];

        $msg = array_merge($msg, [
            'state' => true,
            'type'  => 'success',
            'url'   => $url,
            'time'  => $time,
        ]);

        return $this->message($msg);
    }


    /**
     * 消息提示：error
     *
     * @param mixed $msg 消息内容
     * @param integer $time 跳转或者消息提示时间
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($msg, $time = 5)
    {
        $msg = is_array($msg) ? $msg : ['content' => $msg];

        $msg = array_merge($msg, [
            'state' => false,
            'type'  => 'error',
            'time'  => $time,
        ]);

        return $this->message($msg);
    }

    /**
     * Display the installer welcome page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function welcome(Request $request)
    {
        // 清理缓存
        Artisan::call('optimize:clear');

        // 重新生成 generate
        Artisan::call('key:generate');

        // 创建磁盘连接
        Artisan::call('storage:link');

        // 设置当前主域名
        Artisan::call('env:set', [
            'key'   => 'APP_URL',
            'value' => $request->root(),
        ]);

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $check = true;
        $error = [];

        // php version must > 7.0.0
        if (version_compare(PHP_VERSION, config('install.php_version', '7.3.0'), '<')) {
            $check = false;
            $error['php_version'] = [config('install.php_version', '7.3.0'), PHP_VERSION];
        }

        // php extensions
        foreach (config('install.php_extensions', []) as $extension) {
            if (!extension_loaded($extension)) {
                $check = false;
                $error['php_extensions'][$extension] = false;
            }
        }
        // if function doesn't exist we can't check apache modules
        if (function_exists('apache_get_modules') && $apache_get_modules = apache_get_modules()) {

            foreach (config('install.apache', []) as $requirement) {
                if (!in_array($requirement, apache_get_modules())) {
                    $check = false;
                    $error['apache'][$requirement] = false;
                }
            }
        }

        foreach (config('install.permissions', []) as $path => $permission) {

            $realpath = base_path($path);

            if ($this->app['files']->exists($realpath)) {
                $server_permission = substr(sprintf('%o', fileperms($realpath)), -4);
                if ($server_permission < $permission) {
                    $check = false;
                    $error['permissions'][$path] = [$permission, $server_permission];
                }
            } else {
                $check = false;
                $error['permissions'][$path] = [$permission, null];
            }
        }

        $this->check = $check;
        $this->error = $error;

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function config(ConfigRepository $config, Request $request)
    {
        if ($request->isMethod('POST')) {

            // 缓存站点设置，安装完成时最后写入
            $request->session()->put('install.site', $request->input('site'));

            // 缓存管理员数据，安装完成时最后写入
            $request->session()->put('install.admin', $request->input('admin'));

            // 保存session
            $request->session()->save();

            // 测试数据库是否能正常连接
            $env = $request->input('env');

            // 数据库配置
            $config['database.default'] = $env['DB_CONNECTION'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.host'] = $env['DB_HOST'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.port'] = $env['DB_PORT'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.database'] = $env['DB_DATABASE'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.username'] = $env['DB_USERNAME'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.password'] = $env['DB_PASSWORD'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.prefix'] = $env['DB_PREFIX'];

            app(DatabaseManager::class)->purge($env['DB_CONNECTION']);
            app(ConnectionFactory::class)->make($config['database.connections.' . $env['DB_CONNECTION']], $env['DB_CONNECTION']);

            try {
                app('db')->reconnect()->getPdo();

                foreach ($env as $key => $value) {
                    Artisan::call('env:set', ['key' => $key, 'value' => $value]);
                }

                return $this->success('success', route("install.{$this->next}"));
            } catch (PDOException $e) {
                return $this->error($e->getMessage());
            }
        }

        return $this->view();
    }

    /**
     * Display the database check page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function database(Request $request)
    {
        if ($request->isMethod('POST')) {

            $action = $request->input('action');

            // 覆盖安装
            if ($action == 'override') {
                try {
                    Artisan::call('migrate:fresh');
                    return $this->success('success', route("install.{$this->next}"));
                } catch (Exception $e) {
                    return $this->error($e->getMessage());
                }
            }

            // 初始安装
            if ($action == 'init') {
                try {
                    Artisan::call('migrate');
                    return $this->success('success', route("install.{$this->next}"));
                } catch (Exception $e) {
                    return $this->error($e->getMessage());
                }
            }
        }

        $this->installed = false;

        // 判断是否已经安装，如果已经安装，进入提示覆盖页面
        if (Schema::hasTable('migrations') || Schema::hasTable('users')) {
            $this->installed = true;
        }

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modules(Request $request)
    {
        if ($request->isMethod('POST')) {
            // 安装模块
            if ($name = $request->input('name')) {
                Module::findOrFail($name)->install();
                return $this->success($name . ' install success');
            }
        }

        $this->modules = Module::all();

        return $this->view();
    }


    /**
     * Display the installer check page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function finished(Request $request)
    {
        $this->site = $request->session()->get('install.site');
        $this->admin = $request->session()->get('install.admin');

        // 完成安装以前，写入网站设置
        if ($this->app['installed'] == false) {

            // 插入超级管理员
            User::updateOrCreate([
                'username' => $this->admin['username'],
            ], [
                'password'       => \Hash::make($this->admin['password']),
                'model_id'       => 'super',
                'email'          => $this->admin['email'],
                'mobile'         => '',
                'remember_token' => str_random(10),
            ]);

            // 插入站点设置
            Module::findOrFail('site')->setConfig($this->site);

            // 设置为已安装
            Artisan::call('env:set', ['key' => 'APP_INSTALLED', 'value' => 'true']);

            // 重启系统
            Artisan::call('reboot');
        }

        return $this->view();
    }
}
