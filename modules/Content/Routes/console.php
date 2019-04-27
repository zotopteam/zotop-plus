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
Artisan::command('content:publish-future', function () {

    \Modules\Content\Models\Content::where('status','future')->where('publish_at', '<', now())->update([
        'status' => 'publish'
    ]);

    $this->info('Publish the future content success!');

})->describe('Publish the future content!');

