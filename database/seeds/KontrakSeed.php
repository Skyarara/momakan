<?php

use Illuminate\Database\Seeder;

class KontrakSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contract')->insert([
            [
                'corporate_id' =>  DB::table('corporate')->first()->id,
                'contract_code' => 'MKC/1/1',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
