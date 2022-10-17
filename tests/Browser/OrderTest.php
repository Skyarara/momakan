<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class OrderTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testOrder()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()

                // 1. ke menu login
                ->visit('/')

                // 2. memasukkan email dan password admin agar bisa login
                ->type('email', 'admin@dapurbunda.com')
                ->type('password', '123456')

                // 3. menekan tombol login
                ->press('Sign_In')

                // 4. ke menu utama dapur bunda
                ->assertPathIs('/home')

                // 5. mengalihkan ke menu contract
                ->visit('/contract')->pause('1250')

                // 6. menekan tombol list order agar dapat masuk ke menu order
                ->press('#Ord')->pause('2500')

                // 7. menekan tombol buat pesanan
                ->press('#psn')->pause('2500')

                // 8. menekan tombol tambah order
                ->press('#tbho')->pause('1250')

                // 9. menekan tombol melihat detail pesanan
                ->script('punchNewDatas()');

            // 10. dialihkan kembali ke menu order
            $browser
                ->pause('2500')
                ->visit('/contract/order/2')->pause('5000');
        });
    }
}
