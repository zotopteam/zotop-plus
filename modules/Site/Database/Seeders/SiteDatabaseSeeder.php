<?php

namespace Modules\Site\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Support\Eloquent\Model;

class SiteDatabaseSeeder extends Seeder
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
