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

Artisan::command('cms:version', function () {
    $name    = $this->laravel['config']->get('app.name');
    $version = $this->laravel['config']->get('app.version');
    $release = $this->laravel['config']->get('app.release');

    $this->info($name.' version:'.$version.'('.$release.')');

})->describe('Show the version of the cms');
