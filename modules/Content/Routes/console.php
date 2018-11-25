<?php
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
/**
 * 定时发布
 */
Artisan::command('content:publish-future {--schedule}', function () {

    // 记录定时器是否工作
    if ($this->option('schedule') && !config('content.publish_future_schedule', false)) {
        \Modules\Core\Models\Config::set('content', [
            'publish_future_schedule' => true
        ]);
    }

    \Modules\Content\Models\Content::where('status','future')->where('publish_at', '<', now())->update([
        'status' => 'publish'
    ]);

})->describe('Publish the future content!');

