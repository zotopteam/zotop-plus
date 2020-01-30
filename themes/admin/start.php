<?php

use Illuminate\Support\Facades\Event;

Event::listen('themes.admin.deleting', function($theme){
    abort(403, trans('master.forbidden'));
});
