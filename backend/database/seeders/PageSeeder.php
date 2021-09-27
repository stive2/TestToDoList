<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'label'     => "Pages",
            'icon'      => "web",
            'to'        => "pages",
            'statut'    => "Oui",
        ]);
    }
}
