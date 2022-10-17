<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LaporanTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLaporan()
    {
        /*
            Dikarenakan tidak bisa mengatur/mendeteksi "Print Page" jadi ditambahkan pause saja
        */
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                // Menuju Halaman Login
                ->visit('/')
                // Menginput Email dan Password
                ->type('email', 'admin@dapurbunda.com')
                ->type('password', '123456')
                // Menekan Tombol "Sign In"
                ->press('Sign_In')
                // Setelah Login, di cek apakah "Dasbor" tersedia
                ->assertSee('Dasbor')
                // Jika tersedia maka ke url laporan
                ->visit('/laporan')
                // Setelah ke url laporan, di cek lagi apakah "Menampilkan Data Kontrak" tersedia
                ->assertSee('Menampilkan Data Kontrak')->pause(500)
                // Jika tersedia, maka klik button class ".btn-primary"
                ->click('.btn-primary')
                // Kemudian jika muncul modal dengan id "print"
                ->whenAvailable('#print', function ($pr) {
                    // Di cek apakah terdapat text "Mencetak Laporan"
                    $pr->assertSee('Mencetak Laporan')
                        // Jika tersedia, maka "btn-print" di klik
                        ->click('#btn-print');
                })
                // Berikan waktu 10 detik untuk menuju halaman print
                ->pause(10000)
                ->assertSee('Alamat')
                // Berikan 120 Detik untuk Print
                ->pause(3000);
        });
    }
}
