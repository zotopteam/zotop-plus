<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Zotop\Support\Eloquent\Model;

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
        
        //$this->call(CoreUserTableSeeder::class);

        // $this->call("OthersTableSeeder");
    }
}
