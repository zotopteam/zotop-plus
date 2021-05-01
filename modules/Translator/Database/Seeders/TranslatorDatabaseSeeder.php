<?php

namespace Modules\Translator\Database\Seeders;

use Illuminate\Database\Seeder;
use Zotop\Database\Eloquent\Model;

class TranslatorDatabaseSeeder extends Seeder
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
