<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zotop\Modules\Routing\AdminController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

class SchedulingController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('core::scheduling.title');

        $this->tasks = $this->getScheduleEvents();

        return $this->view();
    }

    public function run(Container $app, $index)
    {
        $output = storage_path('logs/schedule-task.output');

        // 执行并输出结果
        $event = $app->make(Schedule::class)->events()[$index];
        $event->sendOutputTo($output);
        $event->run($app);

        // 获取输出结果
        if (file_exists($output) && ($result = file_get_contents($output))) {
            unlink($output);
            return $result;
        }

        return null;
    }

    protected function getScheduleEvents()
    {
        $events = app(Schedule::class)->events();

        $events = collect($events)->transform(function($event, $index) {
            
            // 索引编号
            $event->index = $index;
            
            // 命令和类型
            if ($event instanceof CallbackEvent) {
                $event->type = 'closure';
                $event->cmd = 'closure';
            } else if (Str::contains($event->command, 'artisan')) {
                $command = explode(' ', $event->command);
                $event->type = 'artisan';
                $event->cmd = implode(' ', array_slice($command, 2));                
            } else {
                $event->type = 'command';
                $event->cmd = $event->command;
            }

            return $event;
        });

        debug($events);

        return $events;
    } 
}
