<?php

namespace Modules\Region\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Support\Eloquent\Model;

class RegionDatabaseSeeder extends Seeder
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
