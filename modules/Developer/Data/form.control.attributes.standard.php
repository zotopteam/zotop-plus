<?php
return [

    'type' => [
        'text'     => trans('developer::form.control.attribute.type'),
        'type'     => 'string',
        'required' => true,
    ],

    'id' => [
        'text'     => trans('developer::form.control.attribute.id'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'test',
    ],

    'name' => [
        'text'     => trans('developer::form.control.attribute.name'),
        'type'     => 'string',
        'required' => true,
        'example'  => 'test',
    ],

    'options' => null,

    'value' => [
        'text'     => trans('developer::form.control.attribute.value'),
        'type'     => ['string', 'number'],
        'required' => false,
        'example'  => 'test',
    ],

    'class' => [
        'text'     => trans('developer::form.control.attribute.class'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'm-2',
    ],

    'style' => [
        'text'     => trans('developer::form.control.attribute.style'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'margin:2rem;',
    ],

    'tabindex' => [
        'text'     => trans('developer::form.control.attribute.tabindex'),
        'type'     => 'integer',
        'required' => false,
        'example'  => '-1',
    ],



    'data-*' => [
        'text'     => trans('developer::form.control.attribute.data-*'),
        'type'     => 'mixed',
        'required' => false,
        'example'  => 'data-show="true"',
    ],



];

