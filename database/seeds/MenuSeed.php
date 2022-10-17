<?php

use Illuminate\Database\Seeder;

class MenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->insert([
            [

                'menu_category_id' => 3,
                'name' => 'Ayam Kare',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Mie Kecap Kol',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Kerupuk',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 2,
                'name' => 'Nasi',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Telur Dadar',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Samgor Kentang Tahu',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Udang Tepung',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Kerupuk',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Goreng',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Samgor Tahu Tempe',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Cah Sawi Putih Toge',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Telur Kare',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Mie + Sawi',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Bakar',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Tahu Tempe Goreng',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Lalapan',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Bacem',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Ampal Jagung',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Sayur Bening',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Rica-rica',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Telur Bumbu Bali',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Samgor Tahu Toge',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Cah Sawi',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Geprek',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Tempe Terong Goreng',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Ayam Crispy',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Pecel',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Cah Kangkung',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Bobor Daun Singkong',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 5,
                'name' => 'Tempe Penyet Sambal',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Telur Bumbu Kecap',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Sayur Asam',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 3,
                'name' => 'Telur Bumbu Pedas',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'menu_category_id' => 4,
                'name' => 'Sayur Sop',
                'description' => '.',
                'price' => '1000',
                'image' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

        ]);
        // 1.paket
        // 2.karbo
        // 3.lauk
        // 4.Sayur
        // 5.lauk tambahan
    }
}
