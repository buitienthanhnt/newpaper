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
            ['name' => 'ip', 'value'  => '192.168.100.156', 'type' => 'text'],
            ['name' => 'main', 'value' => 'laravel1', 'type' => 'text'],
            ['name' => 'domain', 'value' => 'localhost', 'type' => 'text'],
            ['name' => 'is_windown', 'value'  => false, 'type' => 'text'],
            ['name' => 'Authorization', 'value'  => null, 'type' => 'text'],
            ['name' => 'contact_address', 'value'  => 'Nam Tân-Trực Nội-Trực Ninh-Nam Định', 'type' => 'text'],
            ['name' => 'contact_phone', 'value'  => '+84 702032201', 'type' => 'text'],
            ['name' => 'head_title', 'value'  => 'Fast News', 'type' => 'text'],
            ['name' => 'head_conten', 'value'  => 'thông tin nhanh và chính xác nhất', 'type' => 'text'],
            ['name' => 'api_key', 'value'  => 'laravel1.com', 'type' => 'text'],
            ['name' => 'default_image', 'value'  => 'https://www.gstatic.com/mobilesdk/180622_mobilesdk/clouds@2x.png', 'type' => 'text'],
            ['name' => 'custom_css', 'value'  => "\/* body{filter: grayscale(1)} */", 'type' => 'text'],
        ]);
    }
}
