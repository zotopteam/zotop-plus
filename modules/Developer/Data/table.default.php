<?php
use Illuminate\Support\Facades\Schema;

$tableName = $module->getLowerName();

if (Schema::hasTable($tableName)) {
    $tableName = $tableName .'_test';
}


$table = [];
$table['name'] = $tableName;
$table['columns'] = [
            ['name'=>'id', 'type'=>'bigint', 'length'=>null, 'nullable'=>0, 'unsigned'=>1, 'increments'=>1, 'default'=>null, 'comment'=>null],
            ['name'=>'title', 'type'=>'string', 'length'=>'200', 'nullable'=>1, 'unsigned'=>0, 'increments'=>0, 'default'=>null, 'comment'=>'Title'],
            ['name'=>'slug', 'type'=>'char', 'length'=>'128', 'nullable'=>1, 'unsigned'=>0, 'increments'=>0, 'default'=>null, 'comment'=>'Slug'],
            ['name'=>'image', 'type'=>'string', 'length'=>'255', 'nullable'=>1, 'unsigned'=>0, 'increments'=>0, 'default'=>null, 'comment'=>'Cover image'],
            ['name'=>'content', 'type'=>'text', 'length'=>null, 'nullable'=>1, 'unsigned'=>0, 'increments'=>0, 'default'=>null, 'comment'=>'Content'],
            ['name'=>'money', 'type'=>'decimal', 'length'=>'10,2', 'nullable'=>0, 'unsigned'=>1, 'increments'=>0, 'default'=>0.0, 'comment'=>'Money'],
            ['name'=>'sort', 'type'=>'mediumint', 'length'=>'10', 'nullable'=>0, 'unsigned'=>1, 'increments'=>0, 'default'=>0, 'comment'=>'Sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>0, 'unsigned'=>0, 'increments'=>0, 'default'=>1, 'comment'=>'Status'],            
        ];
$table['indexes'] = [
            ['name'=>'PRIMARY','type'=>'primary','columns'=>['id']],
            ['name'=>'title','type'=>'index','columns'=>['title']],
            ['name'=>'slug','type'=>'unique','columns'=>['slug']],
            ['name'=>'sort_status','type'=>'index','columns'=>['sort','status']]
        ];

return $table;
