<?php

use Illuminate\Database\Seeder;

class CorporateSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('corporate')->insert([
            [
                'name' => 'Thortech Asia Software',
                'address' => 'Jalan Citraland',
                'telp' => '09876554321',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => '3'
            ],
            [
                'name' => 'Warung Pecel Family',
                'address' => 'Jalan P.M Noor',
                'telp' => '09876554321',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'user_id' => '4'
            ]
        ]);
    }
}
