<?php

namespace Modules\Block\Database\Seeders;

use Illuminate\Database\Seeder;
use Zotop\Database\Eloquent\Model;

class BlockDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
