<?php

use Illuminate\Support\Facades\Event;

Event::listen('themes.default.deleting', function($theme){
    abort(403, trans('master.forbidden'));
});
