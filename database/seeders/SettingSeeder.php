<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'name'=>'Contest Rules',
                'slug'=>'contest-rules',
                'type'=>'html_editor',
                'options'=>'',
                'value'=>'',
            ],
        ]);
    }
}
