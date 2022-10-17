<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ConfigLaporanTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    // Menuju Halaman Login
                    ->visit('/login')
                    // Menginput Email dan Password
                    ->type('email','admin@dapurbunda.com')
                    ->type('password','123456')
                    // Menekan Tombol "Sign In"
                    ->press('Sign_In')
                    // Setelah Login menuju halaman Laporan
                    ->visit('/laporan')
                    // Menunggu Sampai Melihat "Laporan"
                    ->assertSee('Laporan')
                    ->pause(1000)
                    // Menekan Tombol Konfigurasi
                    ->click('.bg-navy')
                    ->pause(2000)
                    // Menunggu Sampai Melihat "Konfigurasi"
                    ->assertSee('Konfigurasi')
                    ->pause(1000)
                    // Setelah itu menekan tombol Tambah
                    ->click('#tbh')
                    // Setelah mengklik button, maka muncul modal
                    ->whenAvailable('#create', function($tb){
                        // Apakah "Isi Surat" itu tersedia
                        $tb->assertSee('Isi Surat')
                           // Jika tersedia maka mengisi atribut
                           ->type('#isi', "Testing Dusk Laravel")
                           // Menekan Tombol Tambah
                           ->click('#add')
                           // Menunggu 3.5 Detik
                           ->pause(3500);
                    })
                    // Menekan Tombol OK pada Swal
                    ->assertSee('Berhasil Menambahkan Data')
                    ->pause(500)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Disini terjadi Refresh maka Menunggu lagi sekitar 2-5 detik
                    ->pause(2500)
                    // MengEksekusi Script
                    ->script('punchNewData_edit();');

                    // Sesudah Itu maka menekan Tombol Ubah
                    // Dan membuat variabel baru
                    $browser
                    // Setelah mengklik button ubah, muncul modal
                    ->whenAvailable('#show', function($ub){
                        // Apakah "Ubah Isi Surat" itu tersedia
                        $ub->assertSee('Ubah Isi Surat')
                           // Mengisi Atribut
                           ->type('#isi_edit', 'Testing Dusk Laravel_Edit')
                           // Menekan Tombol Ubah dan Menunggu 3 Detik
                           ->click('#update')
                           // Menunggu 3.5 Detik
                           ->pause(3500);
                    })
                    // Menekan Tombol OK pada Swal
                    ->assertSee('Berhasil Mengubah Data')
                    ->pause(500)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Disini terjadi Refresh maka Menunggu lagi sekitar 2-5 detik
                    ->pause(2500)
                    // MengEksekusi Script
                    ->script('punchNewData_delete();');
                    // Membuat Variabel Baru
                    $browser
                    // Setelah mengklik Tombol Hapus maka muncul modal
                    ->whenAvailable('#show', function($hp){
                        // Apakah "Hapus Surat" tersedia
                        $hp->assertSee('Hapus Surat')
                           // Jika tersedia maka menekan tombol hapus pada modal
                           ->click('#update')
                           // Menunggu 2.5 Detik
                           ->pause(2500);
                    })
                    // Menekan Tombol OK pada Swal
                    ->assertSee('Berhasil Menghapus Data')
                    ->pause(500)
                    // Menekan Tombol "OK" dengan class ".swal-button--confirm"
                    ->press('.swal-button--confirm')
                    ->pause(1000)
                    // Disini terjadi Refresh maka Menunggu lagi sekitar 2-5 detik
                    ->pause(2500);
        });
    }
}
