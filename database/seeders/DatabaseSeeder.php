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
        // run: php artisan db:seed

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

        DB::table('config_categories')->insert([
            ['name' => 'thời sự', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'thoi-su', 'type' => 'default'],
            ['name' => 'trong nước', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'trong-nuoc', 'type' => 'default'],
            ['name' => 'quốc tế', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'quoc-te', 'type' => 'default'],
            ['name' => 'công nghệ', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'cong-nghe', 'type' => 'default'],
            ['name' => 'pháp luật', 'active' => 1, 'parent_id'=>2, 'url_alias'=> 'phap-luat', 'type' => 'default'],
            ['name' => 'giải trí', 'active' => 1, 'parent_id'=>2, 'url_alias'=> 'giai-tri', 'type' => 'default'],
            ['name' => 'tin mới', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'tin-moi', 'type' => 'default'],
            ['name' => 'chiến sự', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'chien-su', 'type' => 'time_line'],
            ['name' => 'thời tiết', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'thoi-tiet', 'type' => 'time_line'],
            ['name' => 'kinh tế', 'active' => 1, 'parent_id'=>0, 'url_alias'=> 'kinh-te', 'type' => 'default'],
        ]);

        DB::table('config_categories')->insert([
            ['path' => 'top_category', 'value' => '7&2&3&4&8'],
            ['path' => 'center_category', 'value'  => '7&1&6&5'],
        ]);
    }
}
