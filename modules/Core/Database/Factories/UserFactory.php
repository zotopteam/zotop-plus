<?php

use Faker\Generator as Faker;
use Modules\Core\Models\User;

$factory->define(User::class, function (Faker $faker) {

    static $password;

    //区域化
    //全局本地化：在 config\app.php 文件中加入 'faker_locale' => 'zh_CN'
    //单独本地化：$faker = \Faker\Factory::create('zh_CN');

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 创建时间在更新时间之前
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'username'       => $faker->unique()->name,
        'password'       => $password ?: $password = \Hash::make('123456'),
        'email'          => $faker->unique()->safeEmail,
        'mobile'         => $faker->unique()->phoneNumber,
        'modelid'        => 'admin',
        'nickname'       => $faker->name,
        'gender'         => 1,
        'avatar'         => '',
        'sign'           => $faker->text,
        'remember_token' => str_random(10),
        'created_at'     => $created_at,
        'updated_at'     => $updated_at,       
    ];
});
