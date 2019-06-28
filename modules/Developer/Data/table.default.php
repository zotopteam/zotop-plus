<?php
use Illuminate\Support\Facades\Schema;

$tableName = $module->getLowerName();

if (Schema::hasTable($tableName)) {
    $tableName = $tableName .'_test';
}


$table = [];
$table['name'] = $tableName;
$table['columns'] = [
            ['name'=>'id', 'type'=>'int', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
            ['name'=>'title', 'type'=>'varchar', 'length'=>'200', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'Title'],
            ['name'=>'slug', 'type'=>'char', 'length'=>'128', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'Slug'],
            ['name'=>'image', 'type'=>'varchar', 'length'=>'255', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'Cover image'],
            ['name'=>'content', 'type'=>'text', 'length'=>'', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'Content'],
            ['name'=>'money', 'type'=>'decimal', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'Money'],
            ['name'=>'sort', 'type'=>'mediumint', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'Sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'1', 'comment'=>'Status'],            
        ];
$table['indexes'] = [
            ['name'=>'slug','type'=>'unique','columns'=>'slug'],
            ['name'=>'sort_status','type'=>'index','columns'=>['sort','status']]
        ];



return $table;
