<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeed::class);
        $this->call(UsersSeed::class);
        $this->call(CorporateSeed::class);
        $this->call(KontrakSeed::class);
        $this->call(MenuKategoriSeed::class);
        $this->call(MenuSeed::class);
        $this->call(package::class);
        $this->call(PromoSeed::class);
    }
}
