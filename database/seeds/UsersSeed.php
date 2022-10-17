<?php

use Illuminate\Database\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@dapurbunda.com',
                'password' => bcrypt('123456'),
                'name' => 'admin',
                'phone_number' => '09876554321',
                'role_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'arief@gmail.com',
                'password' => bcrypt('1'),
                'name' => 'Muhamad Arief',
                'phone_number' => '082157269834',
                'role_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'MAriefcoporation@gmail.com',
                'password' => bcrypt('3'),
                'name' => 'Muhamad Arief',
                'phone_number' => '081251229567',
                'role_id' => '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'Pecel@family.com',
                'password' => bcrypt('5'),
                'name' => 'Pecel',
                'phone_number' => '09138288',
                'role_id' => '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],


        ]);
    }
}
// 'role_id' => DB::table('role')->first()->id,
