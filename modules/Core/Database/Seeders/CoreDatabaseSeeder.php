<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\User;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        // for ($i=0; $i <100 ; $i++) {

        //     User::updateOrCreate([
        //         'username' => 'admin'.$i
        //     ],[
        //         'password'       => \Hash::make('admin888'),
        //         'modelid'        => 'admin',
        //         'email'          => str_random(5).$i.'@test.com',
        //         'mobile'         => '139'.str_pad($i, 8, '0', STR_PAD_LEFT),                
        //         'remember_token' => str_random(10),
        //     ]);

        // }  

        // $this->call("OthersTableSeeder");
    }
}
