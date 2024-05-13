<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('core_config')->insert([
            ['name' => 'domain', 'value' => 'localhost'],
            ['name' => 'ip', 'value'  => '192.168.100.156'],
            ['name' => 'is_windown', 'value'  => false],
            ['name' => 'Authorization', 'value'  => null],
            ['name' => 'contact_address', 'value'  => 'Nam Tân-Trực Nội-Trực Ninh-Nam Định'],
            ['name' => 'contact_phone', 'value'  => '+84 702032201'],
            ['name' => 'head_title', 'value'  => 'Fast News'],
            ['name' => 'head_conten', 'value'  => 'thông tin nhanh và chính xác nhất'],
            ['name' => 'api_key', 'value'  => null],
            ['name' => 'default_image', 'value'  => 'https://www.gstatic.com/mobilesdk/180622_mobilesdk/clouds@2x.png']
        ]);
    }
}
