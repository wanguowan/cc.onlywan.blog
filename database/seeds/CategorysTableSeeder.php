<?php

use Illuminate\Database\Seeder;

class CategorysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table( 'categorys' )->delete();
        \DB::table( 'categorys' )->insert([
            [
                'cat_name' => 'PHP',
                'cat_parent' => 0,
                'cat_flag' => 'PHP',
                'cat_desc'  => 'PHP',
                'cat_ip'    => '127.0.0.1'
            ],
            [
                'cat_name' => 'Laravel',
                'cat_parent' => 0,
                'cat_flag' => 'Laravel',
                'cat_desc'  => 'Laravel',
                'cat_ip'    => '127.0.0.1'
            ],
            [
                'cat_name' => 'Linux',
                'cat_parent' => 0,
                'cat_flag' => 'Linux',
                'cat_desc'  => 'Linux',
                'cat_ip'    => '127.0.0.1'
            ],
            [
                'cat_name' => '前端',
                'cat_parent' => 0,
                'cat_flag' => '前端',
                'cat_desc'  => '前端',
                'cat_ip'    => '127.0.0.1'
            ],
            [
                'cat_name' => 'Composer',
                'cat_parent' => 0,
                'cat_flag' => 'Composer',
                'cat_desc'  => 'Composer',
                'cat_ip'    => '127.0.0.1'
            ],
        ]);
    }
}
