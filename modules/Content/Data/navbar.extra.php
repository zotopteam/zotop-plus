<?php
return [
    'model' => [
        'text'  => trans('content::model.title'),
        'href'  => route('content.model.index'),
        'icon'  => 'fa fa-cog',
        'class' => Route::active('content.model.*'),
    ],
];
