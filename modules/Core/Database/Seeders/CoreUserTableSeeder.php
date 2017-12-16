<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;
use Faker\Generator as Faker;

class CoreUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        $faker = app(Faker::class);

        // 调用工厂
        $users = factory(User::class)->times(100)->make()->each(function($user, $index) use ($faker) {
            $user->gender  = $faker->randomElement([0,1,2]);
            $user->modelid = 'admin';
        });

        // 开启hidden字段
        $users->makeVisible(['password', 'token', 'remember_token']);

        User::insert($users->toArray());

        // 更新用户的权限
        $users->each(function($item, $index) {

            //邮箱唯一，根据邮箱查找用户
            $user = User::where('email',$item->email)->first();

            //设置角色
            $user->roles()->attach(1);           
        });

    }
}
